<?php

namespace App\Controllers;

use App\Repositories\BlogRepositoryInterface;
use App\Repositories\GradeRepositoryInterface;
use App\Repositories\ProfileRepositoryInterface;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class ApiController
{
    private BlogRepositoryInterface $blogRepository;
    private GradeRepositoryInterface $gradeRepository;
    private ProfileRepositoryInterface $profileRepository;
    private ResponseFactory $responseFactory;

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        GradeRepositoryInterface $gradeRepository,
        ProfileRepositoryInterface $profileRepository,
        ResponseFactory $responseFactory
    ) {
        $this->blogRepository = $blogRepository;
        $this->gradeRepository = $gradeRepository;
        $this->profileRepository = $profileRepository;
        $this->responseFactory = $responseFactory;
    }

    /**
     * GET /api/blogs
     * Returns a JSON list of all published blogs.
     */
    public function blogs(): Response
    {
        $allBlogs = $this->blogRepository->all();

        // filter to only include published blogs for the public API
        $publishedBlogs = array_filter($allBlogs, function ($blog) {
            return $blog->published == 1;
        });

        // re-index array so it encodes as a JSON array and not an object
        return $this->responseFactory->json(array_values($publishedBlogs));
    }

    /**
     * GET /api/blogs/{id}
     * Returns a single blog by ID.
     */
    public function show(Request $request): Response
    {
        $id = $request->get('id');

        if (!$id) {
            return $this->responseFactory->json(['error' => 'Missing ID'], 400);
        }

        $blog = $this->blogRepository->findById((int) $id);

        if (!$blog) {
            return $this->responseFactory->json(['error' => 'Blog not found'], 404);
        }

        return $this->responseFactory->json($blog);
    }

    /**
     * GET /api/grades
     * Returns a JSON list of all grades.
     */
    public function grades(): Response
    {
        $grades = $this->gradeRepository->all();
        return $this->responseFactory->json($grades);
    }

    /**
     * GET /api/profile
     * Returns JSON data for the professional profile.
     */
    public function profile(): Response
    {
        $profile = $this->profileRepository->get();
        return $this->responseFactory->json($profile);
    }
}
