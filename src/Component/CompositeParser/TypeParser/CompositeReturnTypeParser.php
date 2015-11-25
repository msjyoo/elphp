<?php

namespace Elphp\Component\CompositeParser\TypeParser;

use Elphp\Component\DirectNodeTypeParser\DirectNodeReturnTypeParser;
use Elphp\Component\DocTypeParser\DocReturnTypeParser;
use PhpParser\Node\FunctionLike;

final class CompositeReturnTypeParser
{
    public static function parseNode(FunctionLike $node)
    {
        $docTypes = DocReturnTypeParser::parseNode($node);

        $nodeTypes = DirectNodeReturnTypeParser::parseNode($node);
    }
}