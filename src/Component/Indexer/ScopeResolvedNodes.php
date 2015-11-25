<?php

namespace Elphp\Component\Indexer;

final class ScopeResolvedNodes extends \ArrayObject
{
    // TODO: Somehow type hint that this is an array of Node[]
    public function __construct(array $nodes)
    {
        $this->exchangeArray($nodes);
    }
}