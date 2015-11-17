<?php

namespace Elphp\Component\ScopeResolver\Scope\Definition;

use Elphp\Component\ScopeResolver\NamespaceName;

/**
 * Interface ScopeInterface
 * @package Elphp\Component\ScopeResolver\Scope\Definition
 */
interface ScopeInterface
{
    /**
     * @return NamespaceName
     */
    public function getNamespace();

    /**
     * @return string
     */
    public function __toString();
}