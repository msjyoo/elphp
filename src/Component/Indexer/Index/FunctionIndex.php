<?php

namespace Elphp\Component\Indexer\Index;

use Elphp\Component\ScopeResolver\Scope\Definition\ScopeInterface;
use League\Flysystem\File;

final class FunctionIndex extends \ArrayObject
{
    /**
     * @param File $file
     * @param string $function
     * @param array $arguments
     * @param array $return
     * @param ScopeInterface $scope
     */
    public function add(File $file, $function, array $arguments, array $return, ScopeInterface $scope)
    {
        if($this->offsetExists($function))
        {
            throw new \InvalidArgumentException("Attempting to add duplicate function $function into FunctionIndex");
        }

        $this->offsetSet($function, [
            "file" => $file,
            "function" => $function,
            "arguments" => $arguments,
            "return" => $return,
            "scope" => $scope
        ]);
    }

    public function search($function)
    {
        // TODO: Fuzzy Search
    }
}