<?php

declare(strict_types=1);

namespace VM\SlimHtmlMinify\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use voku\helper\HtmlMin;

class HtmlMinify implements MiddlewareInterface
{
    private $htmlMin;
    private $container;
    private $isActive;

    public function __construct(HtmlMin $htmlMin, ContainerInterface $container, bool $isActive = true)
    {
        $this->htmlMin = $htmlMin;
        $this->container = $container;
        $this->isActive = $isActive;
    }

    public function process(Request $request, RequestHandler $requestHandler): Response
    {
        $response = $requestHandler->handle($request);

        if ($this->isActive) {
            $response = $this->minify($response);
        }

        return $response;
    }

    private function minify(Response $response): Response
    {
        $oldHtml = $response->getBody()->__toString();
        $minifiedHtml = $this->htmlMin->minify($oldHtml);
        $replaceBody = $this->container->get('body');
        $replaceBody->write($minifiedHtml);
        return $response->withBody($replaceBody);
    }
}
