CompositeParser
===============

Combines the following parsers together into a single, easy to use, encapsulated component:

 - DocTypeParser
 - DirectNodeTypeParser

Into these components:
 
 - CompositeReturnTypeParser
 - CompositeParamTypeParser

Note: Any DocType comments take priority over implicit code analysis, unless it is a strict type check e.g. array
type hint in a function argument.