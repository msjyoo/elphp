<?php

namespace sekjun9878\ScopeResolver;

/**
 * Class NamespaceName
 * @package sekjun9878\ScopeResolver
 */
final class NamespaceName extends \ArrayObject
{
    /* In the order of Vendor[0] -> Package[] or ['\'] for root namespace */

    /**
     * @param string|array $namespace
     */
    public function __construct($namespace)
    {
        if(is_string($namespace))
        {
            $namespace = explode("\\", $namespace);
        }

        if(is_array($namespace))
        {
            foreach($namespace as $token)
            {
                $this[] = $token;
            }

            return;
        }

        throw new \InvalidArgumentException;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "\\".implode("\\", $this);
    }
}