<?php

namespace Elphp\Component\ScopeResolver\Scope;

use Elphp\Component\ScopeResolver\NamespaceName;
use Elphp\Component\ScopeResolver\Scope\Definition\ScopeInterface;

/**
 * Class ClassMethodScope
 * @package Elphp\Component\ScopeResolver\Scope
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