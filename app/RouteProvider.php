<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ProfileController;
use App\Controllers\AuthController;
use App\Controllers\ApiController;
use App\Services\AuthMiddleware;
use Framework\Router;
use Framework\RouteProviderInterface;
use Framework\ServiceContainer;

class RouteProvider implements RouteProviderInterface
{
    public function register(Router $router, ServiceContainer $container): void
    {
        $authMiddleware = $container->get(AuthMiddleware::class);

        $homeController = $container->get(HomeController::class);
        $router->addRoute('GET', '/', [$homeController, "index"]);
        $router->addRoute('GET', '/faq', [$homeController, "faq"]);
        $router->addRoute('GET', '/showcase', [$homeController, "showcase"]);

        $dashboardController = $container->get(\App\Controllers\DashboardController::class);
        $router->addRoute('GET', '/dashboard', [$dashboardController, "index"]);
        $router->addRoute('POST', '/dashboard', [$dashboardController, "update"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);

        $profileController = $container->get(ProfileController::class);
        $router->addRoute('GET', '/profile', [$profileController, "index"]);
        $router->addRoute('GET', '/profile/edit', [$profileController, "edit"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('POST', '/profile/edit', [$profileController, "update"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);

        $blogController = $container->get(BlogController::class);
        $router->addRoute('GET', '/blog', [$blogController, "index"]);
        $router->addRoute('GET', '/blogs/create', [$blogController, "create"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('POST', '/blogs', [$blogController, "store"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)', [$blogController, "show"]);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)/edit', [$blogController, "edit"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('POST', '/blogs/(?<slug>[a-zA-Z0-9-]+)/edit', [$blogController, "update"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('GET', '/blogs/(?<slug>[a-zA-Z0-9-]+)/delete', [$blogController, "delete"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('POST', '/blogs/(?<slug>[a-zA-Z0-9-]+)/delete', [$blogController, "destroy"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);
        $router->addRoute('POST', '/blogs/(?<slug>[a-zA-Z0-9-]+)/toggle-publish', [$blogController, "togglePublish"])
            ->addMiddleware([$authMiddleware, 'handleAdmin']);

        $authController = $container->get(AuthController::class);
        $router->addRoute('GET', '/login', [$authController, "loginView"])
            ->addMiddleware([$authMiddleware, 'handleGuest']);
        $router->addRoute('POST', '/login', [$authController, "login"])
            ->addMiddleware([$authMiddleware, 'handleGuest']);
        $router->addRoute('GET', '/register', [$authController, "registerView"])
            ->addMiddleware([$authMiddleware, 'handleGuest']);
        $router->addRoute('POST', '/register', [$authController, "register"])
            ->addMiddleware([$authMiddleware, 'handleGuest']);
        $router->addRoute('GET', '/logout', [$authController, "logout"])
            ->addMiddleware([$authMiddleware, 'handleLogin']);

        // API endppoints
        $apiController = $container->get(ApiController::class);
        $router->addRoute('GET', '/api/blogs', [$apiController, "blogs"]);
        $router->addRoute('GET', '/api/blogs/(?<id>\d+)', [$apiController, "show"]);
        $router->addRoute('GET', '/api/grades', [$apiController, "grades"]);
        $router->addRoute('GET', '/api/profile', [$apiController, "profile"]);
    }
}
