<?php

namespace Elphp\Component\Indexer;

use Elphp\Component\DocTypeParser\DocVarTypeParser;
use Elphp\Component\Indexer\Index\VariableIndex;
use League\Flysystem\File;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

/**
 * Class BasicVariableIndexer
 * @package Elphp\Component\Indexer
 */
final class BasicVariableIndexer
{
    /** @var File $file */
    protected $file;

    /** @var ScopeResolvedNodes $nodes */
    protected $nodes;

    public function __construct(File $file, ScopeResolvedNodes $nodes)
    {
        $this->file = $file;
        $this->nodes = $nodes;
    }

    /**
     * Index it!
     *
     * @return VariableIndex
     */
    public function index()
    {
        $index = new VariableIndex;
        $traverser = new NodeTraverser;
        $visitor = new BasicVariableIndexerVisitor($this->file, $index);

        $traverser->addVisitor($visitor);

        $traverser->traverse($this->nodes->getArrayCopy());

        return $index;
    }
}

/**
 * @internal Anonymous class hack. Should be prohibited from autoloading by not being it's own file.
 *
 * Class BasicVariableIndexerVisitor
 * @package Elphp\Component\Indexer
 */
final class BasicVariableIndexerVisitor extends NodeVisitorAbstract
{
    /** @var File $file */
    protected $file;

    /** @var VariableIndex $index */
    protected $index;

    /**
     * @param File $file
     * @param VariableIndex $index
     */
    public function __construct(File $file, VariableIndex $index)
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
        // PHPDoc @var comments are pretty much the same as actual PHP assignments
        // This node has some comments with var tags in it.
        if($var = DocVarTypeParser::parseNode($node))
        {
            foreach($var as $v)
            {
                try {
                    $this->index->add(
                        $this->file,
                        $node->getAttribute("scopeInner").$v['name'],
                        $v['type'],
                        $node->getAttribute("scopeInner")
                    );
                } catch (\InvalidArgumentException $e) { /* Ignore duplicate VarDoc statements etc. */ }
            }
        }

        // TODO: Assign nodes
    }
}