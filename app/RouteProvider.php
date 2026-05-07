<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use Framework\Router;
use Framework\RouteProviderInterface;
use Framework\ServiceContainer;

class RouteProvider implements RouteProviderInterface
{
    public function register(Router $router, ServiceContainer $container): void
    {
        $homeController = $container->get(HomeController::class);
        $router->addRoute('GET', '/', [$homeController, "index"]);
        $router->addRoute('GET', '/profile', [$homeController, "profile"]);
        $router->addRoute('GET', '/dashboard', [$homeController, "dashboard"]);
        $router->addRoute('GET', '/faq', [$homeController, "faq"]);

        $blogController = $container->get(BlogController::class);
        $router->addRoute('GET', '/blog', [$blogController, "index"]);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)', [$blogController, "show"]);
    }
}
