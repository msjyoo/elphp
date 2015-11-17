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
use Elphp\Component\ScopeResolver\NodeVisitor\ScopeResolver;

$parser        = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);
$traverser     = new NodeTraverser;

// Add the ScopeResolver visitor
$traverser->addVisitor(new ScopeResolver);

$stmts = $parser->parse($code);
$stmts = $traverser->traverse($stmts);

// Now every node will have a "scope" attribute attached.
```