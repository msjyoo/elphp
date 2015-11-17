<?php

namespace Elphp\Component\ScopeResolver;

/**
 * Class NamespaceName
 * @package Elphp\Component\ScopeResolver
 */
final class NamespaceName extends \ArrayObject
{
    /**
     * @param string|array $namespace In the order of Vendor[0] -> Package[] or [] for root namespace, or in str format
     */
    public function __construct($namespace = [])
    {
        if(is_string($namespace))
        {
            // Sanitise empty values from explode e.g. otherwise it prepends some empty values
            $namespace = array_filter(explode("\\", $namespace));
        }

        if(is_array($namespace))
        {
            // Expand slashes that may be present in individual values
            $namespace = array_filter(explode("\\", implode("\\", $namespace)));
            $this->exchangeArray($namespace);
            return;
        }

        throw new \InvalidArgumentException;
    }

    /**
     * @return string Will always start with '\' and end with '\'. e.g. '\' for root namespace
     */
    public function __toString()
    {
        // Have fun figuring out how this works!
        return implode("\\", array_merge([""], $this->getArrayCopy()))."\\";
    }
}