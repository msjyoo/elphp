<?php

namespace Elphp\Component\ScopeResolver\Scope;

use Elphp\Component\ScopeResolver\NamespaceName;
use Elphp\Component\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class NamespaceScope
 * @package Elphp\Component\ScopeResolver\Scope
 */
final class NamespaceScope implements ScopeInterface
{
    /** @var NamespaceName */
    protected $namespace;

    /**
     * @param NamespaceName $namespace
     */
    public function __construct(NamespaceName $namespace)
    {
        $this->namespace = $namespace;
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
        return (string) $this->namespace;
    }
}