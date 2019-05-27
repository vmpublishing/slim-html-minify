<?php

declare(strict_types=1);

namespace VM\SlimHtmlMinify\Tests\Middlewares;

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

use Slim\Http\Response;
use VM\SlimHtmlMinify\Middlewares\HtmlMinify;
use voku\helper\HtmlMin;

class HtmlMinifyTest extends TestCase
{
    public function setup(): void
    {
        $this->htmlMin = $this->buildHtmlMinifyMock();
        $environment = Environment::mock();
        $this->request = Request::createFromEnvironment($environment);
        $this->response = new Response();
        $this->callback = $this->buildHttpCallback();
    }

    public function tearDown(): void
    {
        $this->htmlMin = null;
    }

    public function testInvokeShouldMinifyTheHtml()
    {
        $expectedBody = 'foo';
        $this->htmlMin->expects($this->once())->method('minify')->willReturn($expectedBody);

        $middleware = new HtmlMinify($this->htmlMin, true);
        $response = $middleware($this->request, $this->response, $this->callback);

        $this->assertEquals($expectedBody, $response->getBody()->__toString());
    }

    public function testInvokeShouldNotMinifyWhenInitializedDeactivated()
    {
        $this->htmlMin->expects($this->never())->method('minify');

        $middleware = new HtmlMinify($this->htmlMin, false);
        $response = $middleware($this->request, $this->response, $this->callback);

        $this->assertEquals($response->getBody()->__toString(), $response->getBody()->__toString());
    }

    private function buildHtmlMinifyMock()
    {
        return $this->getMockBuilder(HtmlMin::class)
            ->disableOriginalConstructor()
            ->setMethods(['minify'])
            ->getMock()
        ;
    }

    private function buildHttpCallback(): callable
    {
        return function (Request $request, Response $response) {
            $response = new Response();
            $response->getBody()->write(' f o o ');
            return $response;
        };
    }
}
