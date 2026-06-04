<?php

namespace Framework;

class ResponseFactory
{
    private \Twig\Environment $twig;
    private Session $session;

    public function __construct(bool $debugMode, string $viewsPath, Session $session)
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../' . $viewsPath);
        $twig = new \Twig\Environment($loader, [
            'debug' => $debugMode
        ]);
        if ($debugMode) {
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }
        $this->twig = $twig;
        $this->session = $session;
    }

    /**
     * @param string $view
     * @param array<mixed> $context
     * @return Response
     */
    public function view(string $view, array $context = []): Response
    {
        $response = new Response();

        try {
            $response->responseCode = 200;
            $context['user'] = $this->session->getMany('user');
            $response->body = $this->twig->render($view, $context);
            return $response;
        } catch (\Exception $e) {
            $response->responseCode = 500;
            $response->body = $e->getMessage();
            return $response;
        }
    }

    public function notFound(): Response
    {
        $response = new Response();
        try {
            $response->responseCode = 404;
            $response->body = $this->twig->render('404.html.twig');
            return $response;
        } catch (\Exception $e) {
            $response->responseCode = 500;
            $response->body = $e->getMessage();
            return $response;
        }
    }

    public function internalError(): Response
    {
        $response = new Response();
        try {
            $response->responseCode = 500;
            $response->body = $this->twig->render('500.html.twig');
            return $response;
        } catch (\Exception $e) {
            $response->responseCode = 500;
            $response->body = $e->getMessage();
            return $response;
        }
    }

    public function redirect(string $url): Response
    {
        $response = new Response();
        $response->responseCode = 302;
        $response->headers = ["Location: " . $url];
        return $response;
    }

    /**
     * @param mixed $data
     * @param int $statusCode
     * @return Response
     */
    public function json(mixed $data, int $statusCode = 200): Response
    {
        $response = new Response();
        try {
            $response->responseCode = $statusCode;
            $response->headers = ["Content-Type: application/json"];
            $response->body = json_encode($data, JSON_THROW_ON_ERROR);
            return $response;
        } catch (\JsonException $e) {
            $response->responseCode = 500;
            $response->headers = ["Content-Type: application/json"];
            $response->body = json_encode(['error' => 'Failed to encode JSON data']);
            return $response;
        }
    }
}
