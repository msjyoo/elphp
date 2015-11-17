<?php

namespace Elphp\Component\ScopeResolver\Scope;

use Elphp\Component\ScopeResolver\NamespaceName;
use Elphp\Component\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class FunctionScope
 * @package Elphp\Component\ScopeResolver\Scope
 */
final class FunctionScope implements ScopeInterface
{
    /** @var NamespaceName */
    protected $namespace;

    /** @var string $name */
    protected $name;

    /**
     * @param NamespaceName $namespace
     * @param string $name
     */
    public function __construct(NamespaceName $namespace, $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;
    }

    /**
     * @return NamespaceName
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->namespace.$this->name."()";
    }
}