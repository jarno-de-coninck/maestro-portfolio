<?php

namespace Tests\Integration\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;

use Tests\Integration\IntegrationTestCase;

class UserRepositoryTest extends IntegrationTestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository($this->database);
    }

    public function testFindAll(): void
    {
        $users = $this->repository->all();
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    public function testFindByEmail(): void
    {
        $user = $this->repository->findByEmail('admin@hz.nl');

        $this->assertNotNull($user);
        $this->assertEquals('Admin', $user->name);
        $this->assertEquals('admin@hz.nl', $user->email);
    }

    public function testInsertAndFindById(): void
    {
        $user = new User();
        $user->name = 'Jarno de Coninck';
        $user->email = 'coni0010@hz.nl';
        $user->password = password_hash('password', PASSWORD_DEFAULT);
        $user->role_id = 2; // user rol

        $insertedUser = $this->repository->insert($user);

        $this->assertIsInt($insertedUser->id);
        $this->assertGreaterThan(0, $insertedUser->id);

        $foundUser = $this->repository->findById($insertedUser->id);
        $this->assertNotNull($foundUser);
        $this->assertEquals('Jarno de Coninck', $foundUser->name);
    }

    public function testDelete(): void
    {
        $user = new User();
        $user->name = 'Spam Account';
        $user->email = 'spam@hz.nl';
        $user->password = 'secret';
        $user->role_id = 2;

        $insertedUser = $this->repository->insert($user);
        $id = $insertedUser->id;

        $this->repository->delete($id);

        $foundUser = $this->repository->findById($id);
        $this->assertNull($foundUser);
    }

    public function testUpdate(): void
    {
        $user = $this->repository->findByEmail('admin@hz.nl');
        $this->assertNotNull($user);
        
        $user->name = 'Updated Admin';
        $this->repository->update($user);

        $updatedUser = $this->repository->findByEmail('admin@hz.nl');
        $this->assertEquals('Updated Admin', $updatedUser->name);
    }
}
