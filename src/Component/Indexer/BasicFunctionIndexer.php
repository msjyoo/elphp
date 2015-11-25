<?php

namespace Elphp\Component\Indexer;

use Elphp\Component\DocTypeParser\DocParamTypeParser;
use Elphp\Component\DocTypeParser\DocReturnTypeParser;
use Elphp\Component\Indexer\Index\FunctionIndex;
use League\Flysystem\File;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

/**
 * Class BasicFunctionIndexer
 * @package Elphp\Component\Indexer
 */
final class BasicFunctionIndexer
{
    /** @var File $file */
    protected $file;

    /** @var ScopeResolvedNodes|Node[] $nodes */
    protected $nodes;

    /**
     * @param File $file
     * @param ScopeResolvedNodes $nodes
     */
    public function __construct(File $file, ScopeResolvedNodes $nodes)
    {
        $this->file = $file;
        $this->nodes = $nodes;
    }

    /**
     * @return FunctionIndex
     */
    public function index()
    {
        $index = new FunctionIndex;
        $traverser = new NodeTraverser;
        $visitor = new BasicFunctionIndexerVisitor($this->file, $index);

        $traverser->addVisitor($visitor);

        $traverser->traverse($this->nodes->getArrayCopy());

        return $index;
    }
}

/**
 * @internal Anonymous class hack. Should be prohibited from autoloading by not being it's own file.
 *
 * Class BasicFunctionIndexerVisitor
 * @package Elphp\Component\Indexer
 */
final class BasicFunctionIndexerVisitor extends NodeVisitorAbstract
{
    /** @var File $file */
    protected $file;

    /** @var FunctionIndex $index */
    protected $index;

    /**
     * @param File $file
     * @param FunctionIndex $index
     */
    public function __construct(File $file, FunctionIndex $index)
    {
        $this->file = $file;
        $this->index = $index;
    }

    /**
     * @param Node $node
     *
     * @return null|Node|void
     */
    public function enterNode(Node $node)
    {
        if($node instanceof Node\FunctionLike)
        {
            // TODO: Replace these with more advanced resolvers
            $return = DocReturnTypeParser::parseNode($node);

            $param = DocParamTypeParser::parseNode($node);

            //TODO: There must be a cleaner way than getAttribute - traits maybe?
            try {
                $this->index->add(
                    $this->file,
                    (string) $node->getAttribute("scopeInner"),
                    $param,
                    $return,
                    $node->getAttribute("scope")
                );
            } catch (\InvalidArgumentException $e) { /* TODO: Ignore duplicates - better recovery on parse failure! */ }
        }
    }
}