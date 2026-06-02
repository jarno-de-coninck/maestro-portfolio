<?php

namespace Framework;

class Kernel
{
    private Router $router;

    private ServiceContainer $container;

    private ConfigManager $configManager;

    /**
     * @param array<string, mixed> $config
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->container = new ServiceContainer();

        $this->configManager = new ConfigManager($config);

        $debugMode = $this->configManager->get('APP_ENV') != 'production';

        $session = new Session();
        $session->sessionStart(!$debugMode);
        $this->container->set(Session::class, $session);

        $viewsPath = $this->configManager->get('VIEWS_PATH');
        $responseFactory = new ResponseFactory($debugMode, $viewsPath, $session);
        $this->container->set(ResponseFactory::class, $responseFactory);

        $database = new Database();
        $this->container->set(Database::class, $database);

        $this->router = new Router($responseFactory);
    }

    public function registerRoutes(RouteProviderInterface $routerProvider): void
    {
        $routerProvider->register($this->router, $this->container);
    }

    public function registerServices(ServiceProviderInterface $serviceProvider): void
    {
        $serviceProvider->register($this->container);
    }

    public function handle(Request $request): Response
    {
        return $this->router->dispatch($request);
    }

    public function getDatabase(): Database
    {
        return $this->container->get(Database::class);
    }
}
