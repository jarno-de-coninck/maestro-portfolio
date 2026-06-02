<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use Framework\Session;

class AuthService
{
    private UserRepositoryInterface $userRepository;
    private RoleRepositoryInterface $roleRepository;
    private Session $session;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository,
        Session $session
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->session = $session;
    }

    public function register(User $user, string $password): User
    {
        $role = $this->roleRepository->findByName(Role::USER);
        if ($role) {
            $user->role_id = $role->id;
        }

        $this->userRepository->insert($user);
        return $user;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordStrong(string $password): bool
    {
        if (strlen($password) < 8) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

    public function login(string $email, string $password): bool
    {
        if ($email == null || $password == null) {
            return false;
        }

        $user = $this->userRepository->findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            session_regenerate_id(true);

            $roleName = $user->role_name ?? Role::USER;

            $this->session->setMany('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $roleName,
                'is_admin' => strtolower($roleName) === Role::ADMIN
            ]);

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        $this->session->destroy();
    }
}
