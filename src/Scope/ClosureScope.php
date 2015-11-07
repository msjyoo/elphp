<?php

namespace sekjun9878\ScopeResolver\Scope;

use sekjun9878\ScopeResolver\NamespaceName;
use sekjun9878\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class ClosureScope
 * @package sekjun9878\ScopeResolver\Scope
 */
final class ClosureScope implements ScopeInterface
{
    /** @var ScopeInterface $parent */
    protected $parent;

    /** @var int $identifier */
    protected $identifier;

    /**
     * @param ScopeInterface $parent
     * @param int $identifier Usually the position from start of file / byte number for unique reference
     */
    public function __construct(ScopeInterface $parent, $identifier)
    {
        $this->parent = $parent;
        $this->identifier = $identifier;
    }

    /**
     * @return NamespaceName
     */
    public function getNamespace()
    {
        return $this->parent->getNamespace();
    }

    /**
     * @return ScopeInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if($this->parent instanceof RootNamespaceScope or (strlen($this->parent) === 1 and $this->parent{0} === "\\"))
        {
            return $this->parent."Closure{$this->identifier}";
        }

        return $this->parent."::Closure{$this->identifier}";
    }
}