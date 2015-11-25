<?php

namespace Elphp\Component\Indexer;

use Elphp\Component\DocTypeParser\DocParamTypeParser;
use PhpParser\Node\FunctionLike;

final class ParamTypeParser
{
    public function parseNode(FunctionLike $node)
    {
        $docTypes = DocParamTypeParser::parseNode($node);


    }
}