<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ProfileController;
use Framework\Router;
use Framework\RouteProviderInterface;
use Framework\ServiceContainer;

class RouteProvider implements RouteProviderInterface
{
    public function register(Router $router, ServiceContainer $container): void
    {
        $homeController = $container->get(HomeController::class);
        $router->addRoute('GET', '/', [$homeController, "index"]);
        $router->addRoute('GET', '/dashboard', [$homeController, "dashboard"]);
        $router->addRoute('POST', '/dashboard', [$homeController, "updateDashboard"]);
        $router->addRoute('GET', '/faq', [$homeController, "faq"]);

        $profileController = $container->get(ProfileController::class);
        $router->addRoute('GET', '/profile', [$profileController, "index"]);
        $router->addRoute('GET', '/profile/edit', [$profileController, "edit"]);
        $router->addRoute('POST', '/profile/edit', [$profileController, "update"]);

        $blogController = $container->get(BlogController::class);
        $router->addRoute('GET', '/blog', [$blogController, "index"]);
        $router->addRoute('GET', '/blogs/create', [$blogController, "create"]);
        $router->addRoute('POST', '/blogs', [$blogController, "store"]);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)', [$blogController, "show"]);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)/edit', [$blogController, "edit"]);
        $router->addRoute('POST', '/blogs/(?<slug>[a-zA-Z0-9-]+)/edit', [$blogController, "update"]);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)/delete', [$blogController, "delete"]);
        $router->addRoute('POST', '/blogs/(?<slug>[a-zA-Z0-9-]+)/delete', [$blogController, "destroy"]);
        $router->addRoute('POST', '/blogs/(?<slug>[a-zA-Z0-9-]+)/toggle-publish', [$blogController, "togglePublish"]);
    }
}
