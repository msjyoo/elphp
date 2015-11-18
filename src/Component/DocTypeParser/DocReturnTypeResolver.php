<?php

namespace Elphp\Component\DocTypeParser;

use PhpParser\Comment;
use PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\FunctionLike;

/**
 * Class DocReturnTypeResolver
 * @package Elphp\Component\DocTypeParser
 */
final class DocReturnTypeResolver
{
    /**
     * @param string $string A string of the return tag comment, without the return keyword
     *
     * @return array An array of the return types
     */
    public function resolve($string)
    {
        return (new DocTypeNormaliser)->normalise(
            explode("|", $string)
        );
    }

    /**
     * @param FunctionLike $node A PHP-Parser Node function-like object to resolve return types of
     *
     * @return array A array of the return types, or empty array if no return comment exists
     */
    public function resolveNode(FunctionLike $node)
    {
        // @formatter:off
        /** @var Comment[] $returnComments  */
        $returnComments = $this->filterCommentReturn($node->getAttribute("comments", []));

        if(empty($returnComments))
        {
            return []; // TODO: void? mixed? Maybe verify that comments tag exists?
        }

        /** @var DocBlock\Tag $lastReturnKeyword If multiple "@return" tags, only the last one in last DocBlock used. */
        $lastReturnKeyword = end(
            (new DocBlock(
                end($returnComments)->getReformattedText()
            ))->getTagsByName("return")
        );

        //if($node->)var_dump((explode(" ", $lastReturnKeyword->getContent())));

        // Everything after the tag (after the appending space), is tag content.
        return $this->resolve(strip_tags(explode(" ",
            str_replace("\n", " ", str_replace("\r\n", "\n", $lastReturnKeyword->getContent())))[0]));
    }

    /**
     * Filters an array of comments so that only PHPDoc comments with return tags are returned.
     * If none exist, empty array is returned.
     *
     * @param Comment[] $comments
     *
     * @return Comment[]
     */
    public function filterCommentReturn(array $comments)
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