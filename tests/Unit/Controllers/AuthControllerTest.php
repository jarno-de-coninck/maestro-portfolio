<?php

namespace Tests\Unit\Controllers;

use App\Controllers\AuthController;
use App\Models\User;
use App\Services\AuthService;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    private $authService;
    private $responseFactory;
    private AuthController $controller;

    protected function setUp(): void
    {
        $this->authService = $this->createMock(AuthService::class);
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->controller = new AuthController($this->authService, $this->responseFactory);
    }

    public function testLoginView(): void
    {
        $response = new Response('login');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('auth/login.html.twig')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->loginView(new Request('GET', '/login', [], [])));
    }

    public function testLoginSuccessful(): void
    {
        $request = new Request('POST', '/login', [], ['email' => 'student@hz.nl', 'password' => 'secret']);
        $this->authService->method('login')->willReturn(true);
        
        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->with('/')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->login($request));
    }

    public function testRegisterSuccessful(): void
    {
        $request = new Request('POST', '/register', [], [
            'name' => 'Jarno',
            'email' => 'jarno@hz.nl',
            'password' => 'ValidPass1',
            'password_confirm' => 'ValidPass1'
        ]);

        $this->authService->method('validatePassword')->willReturn([]);
        $this->authService->method('userExists')->willReturn(false);
        $this->authService->expects($this->once())->method('register');

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->with('/login')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->register($request));
    }

    public function testLogout(): void
    {
        $this->authService->expects($this->once())->method('logout');
        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->logout(new Request('GET', '/logout', [], [])));
    }

    public function testLoginFailed(): void
    {
        $request = new Request('POST', '/login', [], ['email' => 'bad@test.com', 'password' => 'bad']);
        $this->authService->method('login')->willReturn(false);
        
        $response = new Response('login');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('auth/login.html.twig', $this->callback(fn($c) => isset($c['error'])))
            ->willReturn($response);

        $this->assertSame($response, $this->controller->login($request));
    }
}
