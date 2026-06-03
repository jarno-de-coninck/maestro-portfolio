<?php

namespace Tests\Unit\Controllers;

use App\Controllers\HomeController;
use App\Models\Grade;
use App\Repositories\GradeRepositoryInterface;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    private $responseFactory;
    private $gradeRepository;
    private HomeController $controller;

    protected function setUp(): void
    {
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->gradeRepository = $this->createMock(GradeRepositoryInterface::class);
        $this->controller = new HomeController($this->responseFactory, $this->gradeRepository);
    }

    public function testIndex(): void
    {
        $response = new Response('index');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('index.html.twig')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->index());
    }

    public function testDashboard(): void
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

        $this->assertSame($response, $this->controller->dashboard());
    }

    public function testUpdateDashboard(): void
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

        $this->assertSame($response, $this->controller->updateDashboard($request));
    }

    public function testFaq(): void
    {
        $response = new Response('faq');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('faq.html.twig')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->faq());
    }
}
