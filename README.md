ScopeResolver for [PHP-Parser](https://github.com/nikic/PHP-Parser)
===================================================================

This is a NodeVisitor component for [nikic/PHP-Parser](https://github.com/nikic/PHP-Parser) that resolves the scope
of classes, functions, namespace and closures, and appends them to every node as an attribute.

This can be useful if you need a unique scope identifier for anything, e.g. checking the scope of a variable.

Currently, only PHP5 is supported but I'm working on PHP7 support.

Usage
-----

Usage is really really really simple.

```php
<?php

use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use sekjun9878\ScopeResolver\NodeVisitor\ScopeResolver;

$parser        = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
$traverser     = new NodeTraverser;

// Add the ScopeResolver visitor
$traverser->addVisitor(new ScopeResolver);

try {
    // parse
    $stmts = $parser->parse($code);

    // traverse
    $stmts = $traverser->traverse($stmts);
} catch (PhpParser\Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}

// Now every node will have a "scope" attribute attached.

array_walk_recursive((array) $stmts, function (Node $node, $key) {
    echo get_class($node)." at line ".$node->getLine()." has a scope of ".$node->getAttribute("scope", "Unknown");
});
```