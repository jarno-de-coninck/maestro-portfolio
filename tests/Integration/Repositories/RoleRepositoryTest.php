<?php

namespace Tests\Integration\Repositories;

use App\Models\Role;
use App\Repositories\RoleRepository;

use Tests\Integration\IntegrationTestCase;

class RoleRepositoryTest extends IntegrationTestCase
{
    private RoleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new RoleRepository($this->database);
    }

    public function testFindAll(): void
    {
        $roles = $this->repository->all();
        $this->assertIsArray($roles);
        $this->assertNotEmpty($roles);
        $this->assertInstanceOf(Role::class, $roles[0]);
    }

    public function testFindByName(): void
    {
        $role = $this->repository->findByName('admin');
        $this->assertNotNull($role);
        $this->assertEquals('admin', $role->name);
    }

    public function testFindById(): void
    {
        $role = $this->repository->findByName('user');
        $foundRole = $this->repository->findById($role->id);
        $this->assertEquals('user', $foundRole->name);
    }
}
