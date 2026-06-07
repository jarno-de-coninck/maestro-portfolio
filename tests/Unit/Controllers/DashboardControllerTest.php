<?php

namespace Tests\Unit\Controllers;

use App\Controllers\DashboardController;
use App\Models\Grade;
use App\Repositories\GradeRepositoryInterface;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;
use PHPUnit\Framework\TestCase;

class DashboardControllerTest extends TestCase
{
    private $responseFactory;
    private $gradeRepository;
    private DashboardController $controller;

    protected function setUp(): void
    {
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->gradeRepository = $this->createMock(GradeRepositoryInterface::class);
        $this->controller = new DashboardController($this->responseFactory, $this->gradeRepository);
    }

    public function testIndex(): void
    {
        $grades = [new Grade()];
        $response = new Response('dashboard');

        $this->gradeRepository->expects($this->once())
            ->method('all')
            ->willReturn($grades);

        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('dashboard.html.twig', ['grades' => $grades])
            ->willReturn($response);

        $this->assertSame($response, $this->controller->index());
    }

    public function testUpdate(): void
    {
        $request = new Request('POST', '/dashboard', [], ['grades' => ['1' => '8.5']]);
        $grade = new Grade();
        $grade->id = 1;

        $this->gradeRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($grade);

        $this->gradeRepository->expects($this->once())
            ->method('update')
            ->with($this->callback(function ($grade) {
                return $grade->grade == 8.5;
            }));

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->with('/dashboard')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->update($request));
    }
}
