<?php

namespace Tests\Integration\Repositories;

use App\Models\Profile;
use App\Repositories\ProfileRepository;

use Tests\Integration\IntegrationTestCase;

class ProfileRepositoryTest extends IntegrationTestCase
{
    private ProfileRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProfileRepository($this->database);
    }

    public function testGet(): void
    {
        $profile = $this->repository->get();
        $this->assertNotNull($profile);
        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertEquals(1, $profile->id);
    }
}
