<?php

namespace Tests\Unit\Controllers;

use App\Controllers\HomeController;
use Framework\Response;
use Framework\ResponseFactory;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    private $responseFactory;
    private HomeController $controller;

    protected function setUp(): void
    {
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->controller = new HomeController($this->responseFactory);
    }

    public function testIndex(): void
    {
        $response = new Response('index');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('index.html.twig')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->index());
    }

    public function testFaq(): void
    {
        $response = new Response('faq');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('faq.html.twig')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->faq());
    }

    public function testShowcase(): void
    {
        $response = new Response('showcase');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('showcase.html.twig')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->showcase());
    }
}
