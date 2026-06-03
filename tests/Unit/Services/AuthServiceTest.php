<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Role;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Services\AuthService;
use Framework\Session;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private $userRepository;
    private $roleRepository;
    private $session;
    private AuthService $authService;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->roleRepository = $this->createMock(RoleRepositoryInterface::class);
        $this->session = $this->createMock(Session::class);
        $this->authService = new AuthService($this->userRepository, $this->roleRepository, $this->session);
    }

    public function testHashPassword(): void
    {
        $password = 'password123';
        $hashed = $this->authService->hashPassword($password);
        $this->assertTrue(password_verify($password, $hashed));
    }

    public function testValidatePassword(): void
    {
        $this->assertNotEmpty($this->authService->validatePassword('short'));
        $this->assertNotEmpty($this->authService->validatePassword('nouppercase1'));
        $this->assertNotEmpty($this->authService->validatePassword('NoNumber'));
        $this->assertEmpty($this->authService->validatePassword('ValidPassword1'));
    }

    public function testLoginSuccessful(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = new User();
        $user->id = 1;
        $user->name = 'Sander';
        $user->email = 'sander@hz.nl';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->role_name = 'user';

        $this->userRepository->method('findByEmail')->willReturn($user);

        $this->session->expects($this->once())->method('setMany');

        $this->assertTrue($this->authService->login('sander@hz.nl', 'password'));
    }

    public function testLoginFailed(): void
    {
        $this->userRepository->method('findByEmail')->willReturn(null);
        $this->assertFalse($this->authService->login('sander@hz.nl', 'password'));
    }

    public function testRegister(): void
    {
        $user = new User();
        $role = new Role();
        $role->id = 2;
        $role->name = 'user';

        $this->roleRepository->method('findByName')->willReturn($role);
        $this->userRepository->expects($this->once())->method('insert');

        $result = $this->authService->register($user, 'password');
        $this->assertEquals(2, $result->role_id);
    }
}
