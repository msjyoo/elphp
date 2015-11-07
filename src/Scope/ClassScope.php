<?php

namespace sekjun9878\ScopeResolver\Scope;

use sekjun9878\ScopeResolver\NamespaceName;
use sekjun9878\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class ClassScope
 * @package sekjun9878\ScopeResolver\Scope
 */
final class ClassScope implements ScopeInterface
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
        return $this->namespace.$this->name;
    }
}