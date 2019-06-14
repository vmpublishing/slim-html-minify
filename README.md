
**WHAT**

PSR-15 html minify middleware with as low dependencies as possible.

This exists for two reasons:
- christianklisch/slim-minify did result in errors, as it just uses some regex.
- slim upgraded to version 4, thus changes its middleware interfaces to the PSR-15 type


**INSTALL**

To install simply use
`composer require vmpublishing/slim-minify-minify:>=2.0.0`

**USE**

As this is basically just a wrapper for voku/html-min ( https://github.com/voku/HtmlMin ), configure that to you liking and pass the instance in, ie:

```
$htmlMin = new HtmlMin();

$htmlMin->doOptimizeViaHtmlDomParser();               // optimize html via "HtmlDomParser()"
$htmlMin->doRemoveComments();                         // remove default HTML comments (depends on "doOptimizeViaHtmlDomParser(true)")
$htmlMin->doSumUpWhitespace();                        // sum-up extra whitespace from the Dom (depends on "doOptimizeViaHtmlDomParser(true)")
$htmlMin->doRemoveWhitespaceAroundTags();             // remove whitespace around tags (depends on "doOptimizeViaHtmlDomParser(true)")
$htmlMin->doOptimizeAttributes();                     // optimize html attributes (depends on "doOptimizeViaHtmlDomParser(true)")
$htmlMin->doRemoveHttpPrefixFromAttributes();         // remove optional "http:"-prefix from attributes (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveDefaultAttributes();                // remove defaults (depends on "doOptimizeAttributes(true)" | disabled by default)
$htmlMin->doRemoveDeprecatedAnchorName();             // remove deprecated anchor-jump (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveDeprecatedScriptCharsetAttribute(); // remove deprecated charset-attribute - the browser will use the charset from the HTTP-Header, anyway (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveDeprecatedTypeFromScriptTag();      // remove deprecated script-mime-types (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveDeprecatedTypeFromStylesheetLink(); // remove "type=text/css" for css links (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveEmptyAttributes();                  // remove some empty attributes (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveValueFromEmptyInput();              // remove 'value=""' from empty <input> (depends on "doOptimizeAttributes(true)")
$htmlMin->doSortCssClassNames();                      // sort css-class-names, for better gzip results (depends on "doOptimizeAttributes(true)")
$htmlMin->doSortHtmlAttributes();                     // sort html-attributes, for better gzip results (depends on "doOptimizeAttributes(true)")
$htmlMin->doRemoveSpacesBetweenTags();                // remove more (aggressive) spaces in the dom (disabled by default)

$middleware = new \VM\SlimHtmlMinify\Middlewares\HtmlMinify($htmlMin, true);

# and for slim
$app->add($middleware);

# or the lazy version depending on a container
$app->add(HtmlMinify::class);

# or just add it to specific routes / groups
```
