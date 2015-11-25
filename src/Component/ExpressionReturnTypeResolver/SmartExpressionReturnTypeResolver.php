<?php

namespace Elphp\Component\ExpressionReturnTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr;

/**
 * Class SmartExpressionReturnTypeResolver
 * @package Elphp\Component\FunctionReturnTypeResolver
 */
final class SmartExpressionReturnTypeResolver
{
    /**
     * @param Expr $expr
     *
     * @return array Returns an array of types, or an empty array if the expression doesn't return a value
     */
    public static function resolve(Expr $expr)
    {
        if(!$expr->getAttribute("scope") or !$expr->getAttribute("scopeInner"))
        {
            throw new \InvalidArgumentException("Expr must have its scope and scopeInner resolved");
        }

        switch(get_class($expr))
        {
            default:
                return BasicExpressionReturnTypeResolver::resolve($expr);
        }
    }
}