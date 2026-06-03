<?php

namespace Tests\Unit\Controllers;

use App\Controllers\ProfileController;
use App\Models\Profile;
use App\Repositories\ProfileRepositoryInterface;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;
use PHPUnit\Framework\TestCase;

class ProfileControllerTest extends TestCase
{
    private $responseFactory;
    private $profileRepository;
    private ProfileController $controller;

    protected function setUp(): void
    {
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->profileRepository = $this->createMock(ProfileRepositoryInterface::class);
        $this->controller = new ProfileController($this->responseFactory, $this->profileRepository);
    }

    public function testIndex(): void
    {
        $profile = new Profile();
        $this->profileRepository->method('get')->willReturn($profile);

        $response = new Response('profile');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('profile.html.twig', ['profile' => $profile])
            ->willReturn($response);

        $this->assertSame($response, $this->controller->index());
    }

    public function testUpdate(): void
    {
        $profile = new Profile();
        $this->profileRepository->method('get')->willReturn($profile);
        $this->profileRepository->expects($this->once())->method('update');

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->with('/profile')
            ->willReturn($response);

        $request = new Request('POST', '/profile', [], ['name' => 'Jarno (Updated)']);
        $this->assertSame($response, $this->controller->update($request));
    }
}
