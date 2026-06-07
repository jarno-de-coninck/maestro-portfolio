<?php

namespace App\Controllers;

use App\Models\Grade;
use App\Repositories\GradeRepositoryInterface;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class DashboardController
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
        $grades = $this->gradeRepository->all();
        return $this->responseFactory->view("dashboard.html.twig", [
            "grades" => $grades
        ]);
    }

    public function update(Request $request): Response
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
}
