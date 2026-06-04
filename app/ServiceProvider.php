<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ProfileController;
use App\Controllers\AuthController;
use App\Controllers\ApiController;
use App\Repositories\BlogRepository;
use App\Repositories\GradeRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Services\AuthMiddleware;
use App\Services\AuthService;
use Framework\Database;
use Framework\ResponseFactory;
use Framework\ServiceContainer;
use Framework\ServiceProviderInterface;
use Framework\Session;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $container): void
    {
        $responseFactory = $container->get(ResponseFactory::class);
        $database = $container->get(Database::class);
        $session = $container->get(Session::class);

        $authMiddleware = new AuthMiddleware($session, $responseFactory);
        $container->set(AuthMiddleware::class, $authMiddleware);

        $userRepository = new UserRepository($database);
        $roleRepository = new RoleRepository($database);
        $authService = new AuthService($userRepository, $roleRepository, $session);
        $authController = new AuthController($authService, $responseFactory);
        $container->set(AuthController::class, $authController);

        $gradeRepository = new GradeRepository($database);

        $homeController = new HomeController($responseFactory, $gradeRepository);
        $container->set(HomeController::class, $homeController);

        $blogRepository = new BlogRepository($database);

        $blogController = new BlogController($responseFactory, $blogRepository, $authMiddleware);
        $container->set(BlogController::class, $blogController);

        $profileRepository = new ProfileRepository($database);
        $profileController = new ProfileController($responseFactory, $profileRepository);
        $container->set(ProfileController::class, $profileController);

        $apiController = new ApiController($blogRepository, $gradeRepository, $profileRepository, $responseFactory);
        $container->set(ApiController::class, $apiController);
    }
}
