<?php

namespace App;

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\ProfileController;
use App\Repositories\BlogRepository;
use App\Repositories\GradeRepository;
use App\Repositories\ProfileRepository;
use Framework\Database;
use Framework\ResponseFactory;
use Framework\ServiceContainer;
use Framework\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $container): void
    {
        $responseFactory = $container->get(ResponseFactory::class);
        $database = $container->get(Database::class);

        $gradeRepository = new GradeRepository($database);

        $homeController = new HomeController($responseFactory, $gradeRepository);
        $container->set(HomeController::class, $homeController);

        $blogRepository = new BlogRepository($database);

        $blogController = new BlogController($responseFactory, $blogRepository);
        $container->set(BlogController::class, $blogController);

        $profileRepository = new ProfileRepository($database);
        $profileController = new ProfileController($responseFactory, $profileRepository);
        $container->set(ProfileController::class, $profileController);
    }
}
