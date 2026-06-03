<?php

namespace Tests\Integration\Repositories;

use App\Models\Grade;
use App\Repositories\GradeRepository;

use Tests\Integration\IntegrationTestCase;

class GradeRepositoryTest extends IntegrationTestCase
{
    private GradeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GradeRepository($this->database);
    }

    public function testFindAll(): void
    {
        $grades = $this->repository->all();
        $this->assertIsArray($grades);
        $this->assertNotEmpty($grades);
        $this->assertInstanceOf(Grade::class, $grades[0]);
    }

    public function testInsertAndFindById(): void
    {
        $grade = new Grade();
        $grade->course_name = 'Framework Project 1';
        $grade->test_name = 'Code Assessment';
        $grade->ec = 5.0;
        $grade->quarter = 'Q3';
        $grade->grade = 8.5;

        $insertedGrade = $this->repository->insert($grade);
        $this->assertIsInt($insertedGrade->id);

        $foundGrade = $this->repository->findById($insertedGrade->id);
        $this->assertNotNull($foundGrade);
        $this->assertEquals('Framework Project 1', $foundGrade->course_name);
    }
}
