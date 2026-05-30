<?php

namespace App\Controllers;

use App\Repositories\ProfileRepositoryInterface;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;

class ProfileController
{
    private ResponseFactory $responseFactory;
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(
        ResponseFactory $responseFactory,
        ProfileRepositoryInterface $profileRepository
    ) {
        $this->responseFactory = $responseFactory;
        $this->profileRepository = $profileRepository;
    }
    public function index(): Response
    {
        $profile = $this->profileRepository->get();

        if (!isset($profile)) {
            $this->responseFactory->notFound();
        }

        return $this->responseFactory->view("profile.html.twig", ["profile" => $profile]);
    }

    public function edit(): Response
    {
        $profile = $this->profileRepository->get();
        return $this->responseFactory->view("profile/edit.html.twig", ["profile" => $profile]);
    }

    public function update(Request $request): Response
    {
        $profile = $this->profileRepository->get();
        if ($profile) {
            $profile->name = $request->get('name') ?? $profile->name;
            $profile->bio = $request->get('bio') ?? $profile->bio;
            $profile->characteristics = $request->get('characteristics') ?? $profile->characteristics;
            $profile->skills = $request->get('skills') ?? $profile->skills;
            $profile->linkedin_url = $request->get('linkedin_url') ?? $profile->linkedin_url;
            $profile->github_url = $request->get('github_url') ?? $profile->github_url;
            $this->profileRepository->update($profile);
        }

        return $this->responseFactory->redirect('/profile');
    }
}
