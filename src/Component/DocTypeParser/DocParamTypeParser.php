<?php

namespace Elphp\Component\DocTypeParser;

use PhpParser\Comment;
use PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\FunctionLike;

use function Elphp\Component\ArrayTools\array_flatten;

/**
 * Class DocParamTypeParser
 * @package Elphp\Component\DocTypeParser
 */
final class DocParamTypeParser
{
    /**
     * @param string $string A string of the param tag comment, without the param keyword
     * @param int $posCount Specifies the position of positional param tag. Defaults to 0.
     *
     * @return array An array of the param in the form of ["pos" => 0, "name" => "$var", "type" => ["integer", "bool"]]
     */
    public static function parse($string, $posCount = 0)
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
        else if(count($param) === 1 and $param[0]{0} !== '$')
        {
            $name = '#'.$posCount;
            $type = $param[0];
        }
        else
        {
            // This can also happen sometimes e.g. #0 object EasyRdf_Graph  $graphA  The first graph to be compared
            // Notice the lack of |
            // TODO: But for now, we ignore this error until we can put in better error recovery

            $name = '#'.$posCount;
            $type = "";

            //throw new \InvalidArgumentException("Invalid comment specified. #$posCount $string");
        }

        $type = DocTypeNormaliser::normalise(
            explode("|", $type)
        );

        return ["pos" => $posCount, "name" => $name, "type" => $type];
    }

    // TODO: Maybe refactor resolveNode to use resolveChunk? Or not, decide later
    public static function parseChunk()
    {
        throw new \LogicException("Method Not Implemented - TODO");
    }

    /**
     * @param FunctionLike $node A PHP-Parser Node function-like object to resolve param types of
     *
     * @return array A array of the param arguments and types (if possible), or empty array if no arguments accepted
     */
    public static function parseNode(FunctionLike $node)
    {
        /** @var Comment[] $paramComments */
        $paramComments = self::filterComments($node->getAttribute("comments", []));

        if(empty($paramComments))
        {
            return [];
        }

        $posCount = 0; // Used for positional @param tags e.g. without $varName

        $params = array_unique(array_map(function (DocBlock\Tag $param) use (&$posCount) {
            $param = DocTypeNormaliser::sanitise($param->getContent());

            $param = self::parse($param, $posCount++);
            // Below code can be used to selectively increment posCount - remove ++ above
            // $posCount = ($param["name"]{0} === "#") ? $posCount + 1 : $posCount;

            return $param;
        }, array_flatten(array_map(function (Comment $comment) {
            return (new DocBlock($comment->getReformattedText()))->getTagsByName("param");
        }, $paramComments))), SORT_REGULAR);

        // ["$var" => ["pos" => 0, "name" => "$var", "type" => ["bool", "\Class"]]]
        // ["#1" => ["pos" => 1, "name" => "#1", "type" => ["bool", "\Class"]]]
        return array_combine(array_map(function ($param) {
            return $param["name"];
        }, $params), $params);
    }

    /**
     * Filters an array of comments so that only PHPDoc comments with param tags are returned.
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
                return (new DocBlock($comment->getReformattedText()))->hasTag("param");
            }
        );
        // @formatter:on
    }
}