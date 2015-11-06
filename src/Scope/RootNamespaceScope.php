<?php

namespace sekjun9878\ScopeResolver\Scope;

use sekjun9878\ScopeResolver\NamespaceName;
use sekjun9878\ScopeResolver\Scope\Definition\ScopeInterface;

final class RootNamespaceScope implements ScopeInterface
{
    /** @var NamespaceName $namespace */
    protected $namespace;

    public function __construct()
    {
        $this->namespace = new NamespaceName([]);
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
    public function __toString()
    {
        return "\\";
    }
}