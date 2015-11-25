<?php

namespace Elphp\Component\Indexer\Index;

use Elphp\Component\ScopeResolver\Scope\Definition\ScopeInterface;
use League\Flysystem\File;

final class VariableIndex extends \ArrayObject
{
    public function add(File $file, $name, array $type, ScopeInterface $scope)
    {
        if($this->offsetExists((string) $name) or (strpos($name, "$") === false))
        {
            // The checking for invalid variable needs to be done, in case null or \ is given - they're valid but should be ignored.
            // We throw an exception so if the caller wants those ignored as desired behaviour, it should catch it.
            // This is done for absolutely no particular reason. Seriously! Maybe except for double checking entries.
            throw new \InvalidArgumentException("Attempting to add duplicate or invalid variable '$name' into VariableIndex");
        }

        $this->offsetSet($name, [
            "file" => $file,
            "name" => $name,
            "type" => $type,
            "scope" => $scope
        ]);
    }

    public function search($name)
    {
        // TODO: Fuzzy Search
    }
}