<?php

namespace Elphp\Component\DocTypeParser;

use function Elphp\Component\ArrayTools\first;
use function Elphp\Component\ArrayTools\array_flatten;
use PhpParser\Comment;
use PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Property;

/**
 * Class DocVarTypeParser
 * @package Elphp\Component\DocTypeParser
 */
final class DocVarTypeParser
{
    /**
     * @param string $string A string of the var tag comment, without the var keyword
     *
     * @return array An array of the var type in the form of ["name" => "$var", "type" => ["integer", "bool"]]
     */
    public static function parse($string)
    {
        $param = array_filter(explode(" ", $string)); // Filter excess spaces
        array_splice($param, 2); // Remove non-essential components e.g. comments

        if(count($param) === 2 and $param[0]{0} === '$')
        {
            $name = $param[0];
            $type = $param[1];
        }
        else if(count($param) === 2 and $param[1]{0} === '$')
        {
            $name = $param[1];
            $type = $param[0];
        }
        else if(count($param) === 1 and $param[0]{0} === '$')
        {
            $name = $param[0];
            $type = "";
        }
        else if(count($param) === 1)
        {
            // Whatever comes after has this type. e.g. name is dependent on future assignment
            $name = null;
            $type = $param[0];
        }
        else
        {
            // TODO: string[] regular expressions for replacing disallowed characters in file name
            // TODO: Cases like above, we can no longer guarantee that given string will be a type
            // TODO: We may accidentally inject an invalid type into the index.
            // But for now, ignore those edge cases.

            // Whatever comes after has this type. e.g. name is dependent on future assignment
            $name = null;
            $type = $param[0];
            //throw new \InvalidArgumentException("Invalid comment specified. $string");
        }

        $type = DocTypeNormaliser::normalise(
            explode("|", $type)
        );

        return ["name" => $name, "type" => $type];
    }

    /**
     * @param Node $node A PHP-Parser Node object to parse var comments of
     *
     * @return array An array of the types, or empty array if no return comment exists
     */
    public static function parseNode(Node $node)
    {
        /** @var Comment[] $varComments */
        $varComments = self::filterComments($node->getAttribute("comments", []));

        if(empty($varComments))
        {
            return [];
        }

        return array_filter(array_unique(array_map(function (DocBlock\Tag $var) use ($node) {
            $var = DocTypeNormaliser::sanitise($var->getContent());

            return self::resolveImplicitVarName(
                self::parse($var), $node
            );
        }, array_flatten(array_map(function (Comment $comment) {
            return (new DocBlock($comment->getReformattedText()))->getTagsByName("var");
        }, $varComments))), SORT_REGULAR), function ($element) {
            var_dump($element);
            return $element['name'] !== null; // Filter null names - see self::resolveImplicitVarName()
        });
    }

    /**
     * Filters an array of comments so that only PHPDoc comments with var tags are returned.
     * If none exist, empty array is returned.
     *
     * @param Comment[] $comments
     *
     * @return Comment[]
     */
    public static function filterComments(array $comments)
    {
        // @formatter:off
        return array_filter(
            array_filter($comments,
                function (Comment $comment) {
                    // Filter out normal PHP comments
                    return (strpos($comment->getReformattedText(), "/**") !== false);
                }
            ),
            function (Comment $comment) {
                // Filter for param tags
                return (new DocBlock($comment->getReformattedText()))->hasTag("var");
            }
        );
        // @formatter:on
    }

    /**
     * @param array $var An array of the var type in the form of ["name" => "$var", "type" => ["integer", "bool"]]
     * @param Node $node The node in which the $var belongs to.
     *
     * @return array An array of form $var, with the name deduced from $node.
     */
    public static function resolveImplicitVarName(array $var, Node $node)
    {
        if($var['name'] === null)
        {
            switch(get_class($node))
            {
                case Assign::class:
                    /** @var Assign $node */
                    $node = $node->var;
                    /** @var Variable $node */
                    $var['name'] = "$".$node->name;
                    break;
                case Property::class:
                    /** @var Property $node */
                    $var['name'] = "$".first($node->props)->name;
                    break;
                default:
                    // TODO COMPLETE: By default, the var name will stay null
                    //throw new \LogicException("Unimplemented var node ".get_class($node)." detected!");
            }
        }

        return $var;
    }
}