<?php

namespace sekjun9878\ScopeResolver\Scope\Definition;

use sekjun9878\ScopeResolver\NamespaceName;

/**
 * Interface ScopeInterface
 * @package sekjun9878\ScopeResolver\Scope\Definition
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