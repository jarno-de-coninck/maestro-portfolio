<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use Framework\ResponseFactory;
use Framework\ServiceContainer;
use Framework\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $container): void
    {
        $responseFactory = $container->get(ResponseFactory::class);

        $homeController = new HomeController($responseFactory);
        $container->set(HomeController::class, $homeController);

        $blogController = new BlogController($responseFactory);
        $container->set(BlogController::class, $blogController);
    }
}
