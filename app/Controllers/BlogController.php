<?php

namespace App\Controllers;

use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class BlogController
{
    // TODO: i have to add the logics for blogs :sob:
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function index(): Response
    {
        return $this->responseFactory->view("blog.html.twig");
    }

    public function show(Request $request): Response
    {
        $slug = $request->get('slug');

        $viewPath = "blogs/" . $slug . ".html.twig";

        try {
            return $this->responseFactory->view($viewPath);
        } catch (\Exception $e) {
            return $this->responseFactory->notFound();
        }
    }
}
