<?php

require __DIR__."/vendor/autoload.php";

use Elphp\Component\DocTypeParser\DocParamTypeParser;
use Elphp\Component\Indexer\BasicFunctionIndexer;
use Elphp\Component\Indexer\BasicVariableIndexer;
use Elphp\Component\Indexer\ScopeResolvedNodes;
use Elphp\Component\ScopeResolver\NodeVisitor\ScopeResolver;
use PhpParser\Comment;
use PhpParser\Error;
use PhpParser\Node;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\FunctionLike;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

$adapter = new Local(__DIR__.'/');
$filesystem = new Filesystem($adapter);

$lexer = new PhpParser\Lexer(array(
    'usedAttributes' => array(
        'comments', 'startLine', 'endLine', 'startFilePos', 'endFilePos'
    )
));

$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7, $lexer);

$traverser = new NodeTraverser;

$traverser->addVisitor(new ScopeResolver);

$file = $filesystem->get("code.php");
$code = $file->read();
$stmts = $parser->parse($code);
$stmts = $traverser->traverse($stmts);

var_dump((new BasicVariableIndexer($file, new ScopeResolvedNodes($stmts)))->index()->getArrayCopy());