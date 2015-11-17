<?php

namespace Elphp\Component\ScopeResolver\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Elphp\Component\ScopeResolver\NamespaceName;
use Elphp\Component\ScopeResolver\Scope;
use Elphp\Component\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class ScopeResolver
 * @package Elphp\Component\ScopeResolver\NodeVisitor
 *
 * @author Michael Yoo <michael@yoo.id.au>
 * @author Bernhard Reiter <ockham@raz.or.at>
 */
class ScopeResolver extends NodeVisitorAbstract
{
    /** @var NamespaceName $namespace */
    protected $namespace;

    /** @var ScopeInterface[] $scope */
    protected $scope = [];

    public function __construct()
    {
        $this->namespace = new NamespaceName([]);
        $this->scope = [new Scope\NamespaceScope($this->namespace)];
    }

    public function enterNode(Node $node)
    {
        $node->setAttribute("scope", end($this->scope));

        if($node instanceof Stmt\Namespace_ and !empty($node->name)) // False if entering root namespace
        {
            $this->namespace = new NamespaceName($node->name->parts);
            $this->scope[] = new Scope\NamespaceScope($this->namespace);

        }
        elseif($node instanceof Stmt\Class_)
        {
            $this->scope[] = new Scope\ClassScope($this->namespace, $node->name);
        }
        elseif($node instanceof Stmt\Function_)
        {
            $this->scope[] = new Scope\FunctionScope($this->namespace, $node->name);
        }
        elseif($node instanceof Stmt\ClassMethod)
        {
            $this->scope[] = new Scope\ClassMethodScope(end($this->scope), $node->name);
        }
        elseif($node instanceof Expr\Closure)
        {
            $identifier = $node->getAttribute("startFilePos", mt_rand());
            $node->setAttribute("identifier", $identifier); // The Closure node now has identifier for common use

            $this->scope[] = new Scope\ClosureScope(end($this->scope), $identifier);
        }

        $node->setAttribute("scopeInner", end($this->scope));
    }

    public function leaveNode(Node $node)
    {
        if($node instanceof Stmt\Namespace_)
        {
            $this->namespace = new NamespaceName([]);
            $this->scope = [new Scope\NamespaceScope($this->namespace)];
        }

        if($node instanceof Stmt\Class_
            or $node instanceof Stmt\ClassMethod
            or $node instanceof Stmt\Function_
            or $node instanceof Expr\Closure)
        {
            array_pop($this->scope);
        }
    }
}