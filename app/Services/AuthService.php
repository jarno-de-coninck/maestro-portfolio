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

    public function userExists(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param string $password
     * @return string[]
     */
    public function validatePassword(string $password): array
    {
        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password must contain at least one uppercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password must contain at least one number.';
        }
        return $errors;
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
