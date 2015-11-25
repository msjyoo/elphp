<?php

namespace Elphp\Component\Indexer;

use Elphp\Component\ScopeResolver\NodeVisitor\ScopeResolver;
use League\Flysystem\File;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

final class Indexer
{
    public function __construct()
    {

    }

    public static function index(File $file)
    {
        $lexer = new Lexer(array(
            'usedAttributes' => array(
                'comments', 'startLine', 'endLine', 'startFilePos', 'endFilePos'
            )
        ));

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP5, $lexer);

        $traverser = new NodeTraverser;
        $traverser->addVisitor(new ScopeResolver);
        $stmts = $parser->parse($file->read());
        $stmts = new ScopeResolvedNodes($traverser->traverse($stmts));

        $functions = (new BasicFunctionIndexer($file, $stmts))->index();
        $variables = (new BasicVariableIndexer($file, $stmts))->index();

        return [
            "functions" => $functions,
            "variables" => $variables
        ];
    }
}