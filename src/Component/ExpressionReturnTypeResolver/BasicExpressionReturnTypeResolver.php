<?php

namespace Elphp\Component\ExpressionReturnTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr;

/**
 * Class BasicExpressionReturnTypeResolver
 * @package Elphp\Component\FunctionReturnTypeResolver
 */
final class BasicExpressionReturnTypeResolver
{
    /**
     * Resolve the type of an Expression. This only looks at the Expression type, without inspecting it.
     * As such, anything regarding variables etc. will return mixed.
     *
     * @param Expr $expr
     *
     * @return array Returns an array of types, or an empty array if the expression doesn't return a value
     */
    public static function resolve(Expr $expr)
    {
        switch(get_class($expr))
        {
            // TODO COMPLETE: Everything inside AssignOp
            // TODO COMPLETE: Everything inside BinaryOp except Coalesce
            // TODO COMPLETE: Everything inside Cast
            // TODO COMPLETE: Difference between AssignOp and BinaryOp? - AssignOp is +=, BinaryOp is +

            // TODO: What does BinaryOp\Coalesce return?
            // TODO: Maybe both sides of the expression?

            case Expr\AssignOp\BitwiseAnd::class:
            case Expr\AssignOp\BitwiseOr::class:
            case Expr\AssignOp\BitwiseXor::class:

            case Expr\BinaryOp\BitwiseAnd::class:
            case Expr\BinaryOp\BitwiseOr::class:
            case Expr\BinaryOp\BitwiseXor::class:

            case Expr\BinaryOp\BooleanAnd::class:
            case Expr\BinaryOp\BooleanOr::class:

            case Expr\BinaryOp\LogicalAnd::class:
            case Expr\BinaryOp\LogicalOr::class:
            case Expr\BinaryOp\LogicalXor::class:

            case Expr\BinaryOp\Equal::class:
            case Expr\BinaryOp\NotEqual::class:
            case Expr\BinaryOp\Greater::class:
            case Expr\BinaryOp\GreaterOrEqual::class:
            case Expr\BinaryOp\Smaller::class:
            case Expr\BinaryOp\SmallerOrEqual::class:
            case Expr\BinaryOp\Spaceship::class:

            case Expr\BinaryOp\Identical::class:
            case Expr\BinaryOp\NotIdentical::class:

            case Expr\BitwiseNot::class:
            case Expr\BooleanNot::class:

            case Expr\Cast\Bool_::class:
                return ["bool"];
            case Expr\AssignOp\Div::class:
            case Expr\AssignOp\Minus::class:
            case Expr\AssignOp\Mod::class:
            case Expr\AssignOp\Mul::class:
            case Expr\AssignOp\Plus::class:
            case Expr\AssignOp\Pow::class:

            case Expr\BinaryOp\Div::class:
            case Expr\BinaryOp\Minus::class:
            case Expr\BinaryOp\Mod::class:
            case Expr\BinaryOp\Mul::class:
            case Expr\BinaryOp\Plus::class:
            case Expr\BinaryOp\Pow::class:
                return ["int", "float"];
            case Expr\AssignOp\ShiftLeft::class:
            case Expr\AssignOp\ShiftRight::class:
            case Expr\BinaryOp\ShiftLeft::class:
            case Expr\BinaryOp\ShiftRight::class:
            case Expr\Cast\Int_::class:
                return ["int"];
            case Expr\Cast\Double::class:
                return ["float"];
            case Expr\AssignOp\Concat::class:
            case Expr\Cast\String_::class:
                return ["string"];
            case Expr\Array_::class:
            case Expr\Cast\Array_::class:
                return ["array"];
            case Expr\Cast\Object_::class:
                return ["object"];
            case Expr\ArrayDimFetch::class:
                // TODO: Return whatever the sub node array type is
                // TODO: How to find that out??
                // TODO: OH Maybe the $expr will have docComment in it which can be parsed
                return ["mixed"];
            case Expr\ArrayItem::class:
                return [];
            case Expr\Assign::class:
            case Expr\AssignRef::class:
                /** @var Expr\Assign|Expr\AssignOp $expr */
                return self::resolve($expr->expr);
            case Expr\Cast\Unset_::class:
                return ["null"];
            case Expr\New_::class:
                /** @var Expr\New_ $expr */
                //return [$this->functionDocClassNameResolver->resolve((string) $expr->class)];
            case Expr\PropertyFetch::class:
                return ["mixed"]; // TODO: This requires variable parsing
            case Expr\StaticPropertyFetch::class:
                /** @var Expr\StaticPropertyFetch $expr */
                return ["mixed"]; // TODO: This requires variable parsing
            case Expr\MethodCall::class:
            case Expr\StaticCall::class:
                /** @var Expr\MethodCall|Expr\StaticCall $expr */
                return ["mixed"]; // TODO: This requires variable parsing
            case Expr\Variable::class:
                return ["mixed"]; // TODO: This requires variable parsing
                // TODO: If $this, return current class
            case Expr\Ternary::class:
                /** @var Expr\Ternary $expr */
                return array_merge(self::resolve($expr->if), self::resolve($expr->else));
            case Expr\ConstFetch::class:
                /** @var Expr\ConstFetch $expr */
                // TODO: Cleanup
                return ["const " . (string) $expr->getAttribute("scope")->getNamespace().$expr->name];
            case Expr\Instanceof_::class:
                return ["bool"];
            case Expr\FuncCall::class:
                //var_dump($expr);exit;
                return ["mixed"]; // TODO: FIX
            default:
                //return ["mixed"];
                //var_dump($expr);
                throw new \LogicException("Unimplemented scalar type ".get_class($expr)." at line ".$expr->getLine());
        }
    }
}