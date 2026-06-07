<?php

namespace App\Controllers;

use Framework\Response;
use Framework\ResponseFactory;

class HomeController
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function index(): Response
    {
        return $this->responseFactory->view("index.html.twig");
    }

    public function faq(): Response
    {
        return $this->responseFactory->view('faq.html.twig');
    }

    public function showcase(): Response
    {
        return $this->responseFactory->view('showcase.html.twig');
    }
}
