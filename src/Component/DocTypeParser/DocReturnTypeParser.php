<?php

namespace Elphp\Component\DocTypeParser;

use PhpParser\Comment;
use PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\FunctionLike;

use function Elphp\Component\ArrayTools\last;

/**
 * Class DocReturnTypeParser
 * @package Elphp\Component\DocTypeParser
 */
final class DocReturnTypeParser
{
    /**
     * @param string $string A string of the return tag comment, without the return keyword
     *
     * @return array An array of the return types
     */
    public static function parse($string)
    {
        return DocTypeNormaliser::normalise(
            explode("|", strtok($string, " "))
        );
    }

    /**
     * @param FunctionLike $node A PHP-Parser Node function-like object to resolve return types of
     *
     * @return array A array of the return types, or empty array if no return comment exists
     */
    public static function parseNode(FunctionLike $node)
    {
        /** @var Comment[] $returnComments */
        $returnComments = self::filterComments($node->getAttribute("comments", []));

        if(empty($returnComments))
        {
            return []; // TODO: void? mixed? Maybe verify that comments tag exists?
        }

        /** @var DocBlock\Tag $lastReturnKeyword If multiple "@return" tags, only the last one in last DocBlock used. */
        $lastReturnKeyword = last(
            (new DocBlock(
                last($returnComments)->getReformattedText()
            ))->getTagsByName("return")
        );

        // Everything after the tag (after the appending space), is tag content.
        return self::parse(DocTypeNormaliser::sanitise($lastReturnKeyword->getContent()));
    }

    /**
     * Filters an array of comments so that only PHPDoc comments with return tags are returned.
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
                // Filter for return tags
                return (new DocBlock($comment->getReformattedText()))->hasTag("return");
            }
        );
        // @formatter:on
    }
}