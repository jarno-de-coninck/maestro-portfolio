<?php

namespace App\Controllers;

use App\Models\Blog;
use App\Repositories\BlogRepositoryInterface;
use App\Services\AuthMiddleware;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class BlogController
{
    private ResponseFactory $responseFactory;
    private BlogRepositoryInterface $blogRepository;
    private AuthMiddleware $authMiddleware;

    public function __construct(
        ResponseFactory $responseFactory,
        BlogRepositoryInterface $blogRepository,
        AuthMiddleware $authMiddleware
    ) {
        $this->responseFactory = $responseFactory;
        $this->blogRepository = $blogRepository;
        $this->authMiddleware = $authMiddleware;
    }

    public function index(): Response
    {
        $blogs = $this->blogRepository->all();
        return $this->responseFactory->view("blog.html.twig", [
            "blogs" => $blogs
        ]);
    }

    public function show(Request $request): Response
    {
        $slug = $request->get('slug');

        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $blog = $this->blogRepository->findBySlug($slug);

        if (!$blog) {
            return $this->responseFactory->notFound();
        }

        if (!$blog->published && !$this->authMiddleware->isAdmin()) {
            return $this->responseFactory->notFound();
        }

        return $this->responseFactory->view("blogs/show.html.twig", [
            "blog" => $blog
        ]);
    }

    public function create(): Response
    {
        return $this->responseFactory->view("blogs/create.html.twig");
    }

    public function store(Request $request): Response
    {
        $blog = new Blog();
        $blog->title = $request->get('title') ?? '';
        $blog->subtitle = $request->get('subtitle') ?? '';
        $blog->content = $request->get('content') ?? '';
        $blog->preview_image = $request->get('preview_image') ?? '/images/hz-logo.png';
        $blog->header_image = $request->get('header_image') ?? '';
        $blog->published = $request->get('published') ? 1 : 0;

        $baseSlug = strtolower(trim((string) preg_replace('/[^A-Za-z0-9-]+/', '-', $blog->title)));
        $slug = $baseSlug;
        $counter = 2;

        while ($this->blogRepository->findBySlug($slug) !== null) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $blog->slug = $slug;

        $this->blogRepository->insert($blog);

        return $this->responseFactory->redirect('/blogs/' . $blog->slug);
    }

    public function edit(Request $request): Response
    {
        $slug = $request->get('slug');

        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $blog = $this->blogRepository->findBySlug($slug);

        if (!$blog) {
            return $this->responseFactory->notFound();
        }

        return $this->responseFactory->view("blogs/edit.html.twig", [
            "blog" => $blog
        ]);
    }

    public function update(Request $request): Response
    {
        $slug = $request->get('slug');

        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $blog = $this->blogRepository->findBySlug($slug);

        if (!$blog) {
            return $this->responseFactory->notFound();
        }

        $blog->title = $request->get('title') ?? $blog->title;
        $blog->subtitle = $request->get('subtitle') ?? $blog->subtitle;
        $blog->content = $request->get('content') ?? $blog->content;
        $blog->preview_image = $request->get('preview_image') ?? $blog->preview_image;
        $blog->header_image = $request->get('header_image') ?? $blog->header_image;
        $blog->published = $request->get('published') ? 1 : 0;

        $this->blogRepository->update($blog);

        return $this->responseFactory->redirect('/blogs/' . $blog->slug);
    }

    public function delete(Request $request): Response
    {
        $slug = $request->get('slug');

        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $blog = $this->blogRepository->findBySlug($slug);

        if (!$blog) {
            return $this->responseFactory->notFound();
        }

        return $this->responseFactory->view("blogs/delete.html.twig", [
            "blog" => $blog
        ]);
    }

    public function destroy(Request $request): Response
    {
        $slug = $request->get('slug');

        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $blog = $this->blogRepository->findBySlug($slug);

        if (!$blog) {
            return $this->responseFactory->notFound();
        }

        $this->blogRepository->delete($blog->id);

        return $this->responseFactory->redirect('/blog');
    }

    public function togglePublish(Request $request): Response
    {
        $slug = $request->get('slug');

        if ($slug === null) {
            return $this->responseFactory->notFound();
        }

        $blog = $this->blogRepository->findBySlug($slug);

        if (!$blog) {
            return $this->responseFactory->notFound();
        }

        $blog->published = $blog->published ? 0 : 1;
        $this->blogRepository->update($blog);

        return $this->responseFactory->redirect('/blogs/' . $blog->slug);
    }
}
