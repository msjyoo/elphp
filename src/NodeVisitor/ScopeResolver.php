<?php

namespace sekjun9878\ScopeResolver\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use sekjun9878\ScopeResolver\NamespaceName;
use sekjun9878\ScopeResolver\Scope\ClassMethodScope;
use sekjun9878\ScopeResolver\Scope\ClassScope;
use sekjun9878\ScopeResolver\Scope\ClosureScope;
use sekjun9878\ScopeResolver\Scope\Definition\ScopeInterface;
use sekjun9878\ScopeResolver\Scope\FunctionScope;
use sekjun9878\ScopeResolver\Scope\RootNamespaceScope;

/**
 * Class ScopeResolver
 * @package sekjun9878\ScopeResolver\NodeVisitor
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
        $this->scope = [new RootNamespaceScope];
    }

    public function enterNode(Node $node)
    {
        $node->setAttribute("scope", end($this->scope));

        if($node instanceof Stmt\Namespace_ and !empty($node->name))
        {
            $this->namespace = new NamespaceName($node->name->parts);
        }
        elseif($node instanceof Stmt\Class_)
        {
            $this->scope[] = new ClassScope($this->namespace, $node->name);
        }
        elseif($node instanceof Stmt\Function_)
        {
            $this->scope[] = new FunctionScope($this->namespace, $node->name);
        }
        elseif($node instanceof Stmt\ClassMethod)
        {
            $this->scope[] = new ClassMethodScope(end($this->scope), $node->name);
        }
        elseif($node instanceof Expr\Closure)
        {
            $this->scope[] = new ClosureScope(end($this->scope), $node->getAttribute("startFilePos", mt_rand()));
        }
    }

    public function leaveNode(Node $node)
    {
        if($node instanceof Stmt\Namespace_)
        {
            $this->namespace = new NamespaceName([]);
            $this->scope = [new RootNamespaceScope];
        }
        elseif($node instanceof Stmt\Class_
            or $node instanceof Stmt\ClassMethod
            or $node instanceof Stmt\Function_
            or $node instanceof Expr\Closure)
        {
            array_pop($this->scope);
        }
    }
}