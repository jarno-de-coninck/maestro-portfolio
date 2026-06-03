<?php

namespace Tests\Unit\Services;

use App\Services\AuthMiddleware;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;
use Framework\Session;
use PHPUnit\Framework\TestCase;

class AuthMiddlewareTest extends TestCase
{
    private $session;
    private $responseFactory;
    private AuthMiddleware $middleware;

    protected function setUp(): void
    {
        $this->session = $this->createMock(Session::class);
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->middleware = new AuthMiddleware($this->session, $this->responseFactory);
    }

    public function testIsLoggedIn(): void
    {
        $this->session->method('getMany')
            ->willReturnOnConsecutiveCalls(['id' => 1], null);

        $this->assertTrue($this->middleware->isLoggedIn());
        $this->assertFalse($this->middleware->isLoggedIn());
    }

    public function testIsAdmin(): void
    {
        $this->session->method('getMany')
            ->willReturnOnConsecutiveCalls(['role_name' => 'admin'], ['role_name' => 'user']);

        $this->assertTrue($this->middleware->isAdmin());
        $this->assertFalse($this->middleware->isAdmin());
    }

    public function testHandleLoginRedirectsIfGuest(): void
    {
        $this->session->method('getMany')->willReturn(null);
        $response = new Response('redirect', 302);
        $this->responseFactory->method('redirect')->with('/login')->willReturn($response);

        $request = new Request('GET', '/', [], []);
        $next = function () {
            return new Response('ok');
        };

        $this->assertSame($response, $this->middleware->handleLogin($request, $next));
    }

    public function testHandleLoginCallsNextIfLoggedIn(): void
    {
        $this->session->method('getMany')->willReturn(['id' => 1]);
        $request = new Request('GET', '/', [], []);
        $next = function () {
            return new Response('ok');
        };

        $response = $this->middleware->handleLogin($request, $next);
        $this->assertEquals('ok', $response->body);
    }
}
