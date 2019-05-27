<?php

declare(strict_types=1);

namespace VM\SlimHtmlMinify\Middlewares;

use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;
use voku\helper\HtmlMin;

class HtmlMinify
{
    private $htmlMin;
    private $isActive;

    public function __construct(HtmlMin $htmlMin, bool $isActive = true)
    {
        $this->htmlMin = $htmlMin;
        $this->isActive = $isActive;
    }

    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $response = $next($request, $response);

        if ($this->isActive) {
            $response = $this->minify($response);
        }

        return $response;
    }

    private function minify(Response $response): Response
    {
        $oldHtml = $response->getBody()->__toString();
        $minifiedHtml = $this->htmlMin->minify($oldHtml);
        $replaceBody = new Body(fopen('php://temp', 'r+'));
        $replaceBody->write($minifiedHtml);
        return $response->withBody($replaceBody);
    }
}
