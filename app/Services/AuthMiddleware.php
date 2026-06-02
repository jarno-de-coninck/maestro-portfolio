<?php

namespace App\Services;

use App\Models\Role;
use Framework\ResponseFactory;
use Framework\Response;
use Framework\Session;
use Framework\Request;

class AuthMiddleware
{
    private Session $session;
    private ResponseFactory $responseFactory;

    public function __construct(Session $session, ResponseFactory $responseFactory)
    {
        $this->session = $session;
        $this->responseFactory = $responseFactory;
    }

    public function isLoggedIn(): bool
    {
        return $this->session->getMany('user') !== null;
    }

    public function isAdmin(): bool
    {
        $user = $this->session->getMany('user');
        return $user && strtolower($user['role_name']) === Role::ADMIN;
    }

    public function handleLogin(Request $request, callable $next): Response
    {
        if (!$this->isLoggedIn()) {
            return $this->responseFactory->redirect('/login');
        }
        return $next($request);
    }

    public function handleGuest(Request $request, callable $next): Response
    {
        if ($this->isLoggedIn()) {
            return $this->responseFactory->redirect('/');
        }
        return $next($request);
    }

    public function handleAdmin(Request $request, callable $next): Response
    {
        if (!$this->isLoggedIn()) {
            return $this->responseFactory->redirect('/login');
        }
        if (!$this->isAdmin()) {
            return $this->responseFactory->view('404.html.twig');
        }
        return $next($request);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getUser(): ?array
    {
        return $this->session->getMany('user');
    }
}
