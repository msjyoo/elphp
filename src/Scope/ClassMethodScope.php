<?php

namespace sekjun9878\ScopeResolver\Scope;

use sekjun9878\ScopeResolver\NamespaceName;
use sekjun9878\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class ClassMethodScope
 * @package sekjun9878\ScopeResolver\Scope
 */
final class ClassMethodScope implements ScopeInterface
{
    /** @var ScopeInterface $class */
    protected $class;

    /** @var string $name */
    protected $name;

    /**
     * @param ScopeInterface $class
     * @param string $name
     */
    public function __construct(ScopeInterface $class, $name)
    {
        $this->class = $class;
        $this->name = $name;
    }

    /**
     * @return NamespaceName
     */
    public function getNamespace()
    {
        return $this->class->getNamespace();
    }

    /**
     * @return ClassScope
     */
    public function getClass()
    {
        return $this->class;
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
        return $this->class."->".$this->name."()";
    }
}