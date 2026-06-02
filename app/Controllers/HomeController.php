<?php

namespace App\Controllers;

use App\Models\Grade;
use App\Repositories\GradeRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use App\Services\AuthMiddleware;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class HomeController
{
    private ResponseFactory $responseFactory;
    private GradeRepositoryInterface $gradeRepository;

    public function __construct(
        ResponseFactory $responseFactory,
        GradeRepositoryInterface $gradeRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->gradeRepository = $gradeRepository;
    }

    public function index(): Response
    {
        return $this->responseFactory->view("index.html.twig");
    }

    public function dashboard(): Response
    {
        $grades = $this->gradeRepository->all();
        return $this->responseFactory->view("dashboard.html.twig", [
            "grades" => $grades
        ]);
    }

    public function updateDashboard(Request $request): Response
    {
        $gradesData = $request->getMany('grades') ?? [];

        foreach ($gradesData as $id => $gradeValue) {
            $grade = $this->gradeRepository->findById((int)$id);

            if ($grade instanceof Grade) {
                $grade->grade = (float)$gradeValue;
                $this->gradeRepository->update($grade);
            }
        }

        return $this->responseFactory->redirect('/dashboard');
    }

    public function faq(): Response
    {
        return $this->responseFactory->view("faq.html.twig");
    }
}
