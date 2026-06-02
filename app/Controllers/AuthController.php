<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\AuthMiddleware;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class AuthController
{
    private AuthService $authService;
    private ResponseFactory $responseFactory;

    public function __construct(
        AuthService $authService,
        ResponseFactory $responseFactory,
    ) {
        $this->authService = $authService;
        $this->responseFactory = $responseFactory;
    }

    public function loginView(Request $request): Response
    {
        return $this->responseFactory->view('auth/login.html.twig');
    }

    public function login(Request $request): Response
    {
        $email = $request->get('email') ?? '';
        $password = $request->get('password') ?? '';

        if ($this->authService->login($email, $password)) {
            return $this->responseFactory->redirect('/');
        }

        return $this->responseFactory->view('auth/login.html.twig', [
            'error' => 'Invalid email or password.',
            'old' => ['email' => $email]
        ]);
    }

    public function registerView(Request $request): Response
    {
        return $this->responseFactory->view('auth/register.html.twig');
    }

    public function register(Request $request): Response
    {
        $name = $request->get('name') ?? '';
        $email = $request->get('email') ?? '';
        $password = $request->get('password') ?? '';
        $passwordConfirm = $request->get('password_confirm') ?? '';

        if ($password !== $passwordConfirm) {
            return $this->responseFactory->view('auth/register.html.twig', [
                'error' => 'Passwords do not match.',
                'old' => ['name' => $name, 'email' => $email]
            ]);
        }

        if (!$this->authService->isPasswordStrong($password)) {
            $errorMsg = 'Password must be at least 8 characters long, ' .
                'contain at least one uppercase letter and one number.';
            return $this->responseFactory->view('auth/register.html.twig', [
                'error' => $errorMsg,
                'old' => ['name' => $name, 'email' => $email]
            ]);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $this->authService->hashPassword($password);

        try {
            $this->authService->register($user, $password);
            return $this->responseFactory->redirect('/login');
        } catch (\Exception $e) {
            return $this->responseFactory->view('auth/register.html.twig', [
                'error' => 'Could not register user. Email might already be taken.',
                'old' => ['name' => $name, 'email' => $email]
            ]);
        }
    }

    public function logout(Request $request): Response
    {
        $this->authService->logout();
        return $this->responseFactory->redirect('/');
    }
}
