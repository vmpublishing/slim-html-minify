<?php

declare(strict_types=1);

namespace VM\SlimHtmlMinify\Tests\Middlewares;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use VM\SlimHtmlMinify\Middlewares\HtmlMinify;
use voku\helper\HtmlMin;

class HtmlMinifyTest extends TestCase
{
    public function setup(): void
    {
        $this->buildHtmlMinify();
        $this->buildRequestHandler();
        $this->buildResponse();
        $this->buildRequest();
        $this->buildBody();
        $this->buildContainer();
    }

    public function tearDown(): void
    {
        $this->destroyContainer();
        $this->destroyBody();
        $this->destroyRequest();
        $this->destroyResponse();
        $this->destroyRequestHandler();
        $this->destroyHtmlMin();
    }

    public function testInvokeShouldMinifyTheHtml()
    {
        $unminifiedHtml = '  foo  ';
        $minifiedHtml = 'foo';
        $this->requestHandler->expects($this->once())->method('handle')->willReturn($this->response);
        $this->htmlMin->expects($this->once())->method('minify')->willReturn($minifiedHtml);
        $this->container->expects($this->once())->method('get')->with('body')->willReturn($this->body);
        $this->body->expects($this->once())->method('__toString')->willReturn($unminifiedHtml);
        $this->response->expects($this->once())->method('getBody')->willReturn($this->body);
        $this->response->expects($this->once())->method('withBody')->willReturn($this->response);

        $middleware = new HtmlMinify($this->htmlMin, $this->container, true);
        $middleware->process($this->request, $this->requestHandler);
    }

    public function testInvokeShouldNotMinifyWhenInitializedDeactivated()
    {
        $this->requestHandler->expects($this->once())->method('handle')->willReturn($this->response);
        $this->htmlMin->expects($this->never())->method('minify');
        $this->container->expects($this->never())->method('get');
        $this->body->expects($this->never())->method('__toString');

        $middleware = new HtmlMinify($this->htmlMin, $this->container, false);
        $middleware->process($this->request, $this->requestHandler);
    }

    private function buildHtmlMinify(): void
    {
        $this->htmlMin = $this->getMockBuilder(HtmlMin::class)
            ->disableOriginalConstructor()
            ->setMethods(['minify'])
            ->getMock()
        ;
    }

    private function buildRequestHandler(): void
    {
        $this->requestHandler = $this->getMockBuilder(RequestHandler::class)
            ->disableOriginalConstructor()
            ->setMethods(['handle'])
            ->getMock()
        ;
    }

    private function buildResponse(): void
    {
        $this->response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getStatusCode',
                'withStatus',
                'getReasonPhrase',
                'getProtocolVersion',
                'withProtocolVersion',
                'getHeaders',
                'hasHeader',
                'getHeader',
                'getHeaderLine',
                'withHeader',
                'withAddedHeader',
                'withoutHeader',
                'getBody',
                'withBody',
            ])
            ->getMock()
        ;
    }

    private function destroyHtmlMin(): void
    {
        $this->htmlMin = null;
    }

    private function destroyRequestHandler(): void
    {
        $this->requestHandler = null;
    }

    private function destroyResponse(): void
    {
        $this->response = null;
    }

    private function buildRequest(): void
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getServerParams',
                'getCookieParams',
                'withCookieParams',
                'getQueryParams',
                'withQueryParams',
                'getUploadedFiles',
                'withUploadedFiles',
                'getParsedBody',
                'withParsedBody',
                'getAttributes',
                'getAttribute',
                'withAttribute',
                'withoutAttribute',
                'getProtocolVersion',
                'withProtocolVersion',
                'getHeaders',
                'hasHeader',
                'getHeader',
                'getHeaderLine',
                'withHeader',
                'withAddedHeader',
                'withoutHeader',
                'getBody',
                'withBody',
                'getRequestTarget',
                'withRequestTarget',
                'getMethod',
                'withMethod',
                'getUri',
                'withUri',
            ])
            ->getMock()
        ;
    }

    private function destroyRequest(): void
    {
        $this->request = null;
    }

    private function buildBody(): void
    {
        $this->body = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                '__toString',
                'close',
                'detach',
                'getSize',
                'tell',
                'eof',
                'isSeekable',
                'seek',
                'rewind',
                'isWritable',
                'write',
                'isReadable',
                'read',
                'getContents',
                'getMetadata',
            ])
            ->getMock()
        ;
    }

    private function destroyBody(): void
    {
        $this->body = null;
    }

    private function buildContainer(): void
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'has'])
            ->getMock()
        ;
    }

    private function destroyContainer(): void
    {
        $this->container = null;
    }
}
