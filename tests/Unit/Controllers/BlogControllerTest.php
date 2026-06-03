<?php

namespace Tests\Unit\Controllers;

use App\Controllers\BlogController;
use App\Models\Blog;
use App\Repositories\BlogRepositoryInterface;
use App\Services\AuthMiddleware;
use Framework\Request;
use Framework\Response;
use Framework\ResponseFactory;
use PHPUnit\Framework\TestCase;

class BlogControllerTest extends TestCase
{
    private $responseFactory;
    private $blogRepository;
    private $authMiddleware;
    private BlogController $controller;

    protected function setUp(): void
    {
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->blogRepository = $this->createMock(BlogRepositoryInterface::class);
        $this->authMiddleware = $this->createMock(AuthMiddleware::class);
        $this->controller = new BlogController($this->responseFactory, $this->blogRepository, $this->authMiddleware);
    }

    public function testIndex(): void
    {
        $blogs = [new Blog()];
        $this->blogRepository->method('all')->willReturn($blogs);
        
        $response = new Response('blog');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('blog.html.twig', ['blogs' => $blogs])
            ->willReturn($response);

        $this->assertSame($response, $this->controller->index());
    }

    public function testShowSuccessful(): void
    {
        $request = new Request('GET', '/blogs/test', ['slug' => 'test'], []);
        $blog = new Blog();
        $blog->published = 1;

        $this->blogRepository->method('findBySlug')->with('test')->willReturn($blog);
        
        $response = new Response('show');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('blogs/show.html.twig', ['blog' => $blog])
            ->willReturn($response);

        $this->assertSame($response, $this->controller->show($request));
    }

    public function testShowUnpublishedForbidden(): void
    {
        $request = new Request('GET', '/blogs/test', ['slug' => 'test'], []);
        $blog = new Blog();
        $blog->published = 0;

        $this->blogRepository->method('findBySlug')->with('test')->willReturn($blog);
        $this->authMiddleware->method('isAdmin')->willReturn(false);
        
        $response = new Response('404', 404);
        $this->responseFactory->expects($this->once())
            ->method('notFound')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->show($request));
    }

    public function testStore(): void
    {
        $request = new Request('POST', '/blogs', [], [
            'title' => 'New Blog',
            'content' => 'Content'
        ]);

        $this->blogRepository->method('findBySlug')->willReturn(null);
        $this->blogRepository->expects($this->once())->method('insert');

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->store($request));
    }

    public function testEdit(): void
    {
        $request = new Request('GET', '/blogs/test/edit', ['slug' => 'test'], []);
        $blog = new Blog();
        $this->blogRepository->method('findBySlug')->with('test')->willReturn($blog);

        $response = new Response('edit');
        $this->responseFactory->expects($this->once())
            ->method('view')
            ->with('blogs/edit.html.twig', ['blog' => $blog])
            ->willReturn($response);

        $this->assertSame($response, $this->controller->edit($request));
    }

    public function testUpdate(): void
    {
        $request = new Request('POST', '/blogs/test/edit', ['slug' => 'test'], ['title' => 'My First Semester at HZ - Updated']);
        $blog = new Blog();
        $blog->slug = 'test';
        $blog->title = 'My First Semester at HZ';
        $blog->subtitle = 'Learning OOP and Databases';
        $blog->content = 'It was a great experience...';
        $blog->preview_image = '/img.png';
        $blog->header_image = '/header.png';
        $blog->published = 0;
        
        $this->blogRepository->method('findBySlug')->with('test')->willReturn($blog);
        $this->blogRepository->expects($this->once())->method('update');

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->with('/blogs/test')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->update($request));
        $this->assertEquals('My First Semester at HZ - Updated', $blog->title);
    }

    public function testDestroy(): void
    {
        $request = new Request('POST', '/blogs/test/delete', ['slug' => 'test'], []);
        $blog = new Blog();
        $blog->id = 123;
        $this->blogRepository->method('findBySlug')->with('test')->willReturn($blog);
        $this->blogRepository->expects($this->once())->method('delete')->with(123);

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->with('/blog')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->destroy($request));
    }

    public function testTogglePublish(): void
    {
        $request = new Request('POST', '/blogs/test/toggle', ['slug' => 'test'], []);
        $blog = new Blog();
        $blog->slug = 'test';
        $blog->published = 0;
        $this->blogRepository->method('findBySlug')->with('test')->willReturn($blog);
        $this->blogRepository->expects($this->once())->method('update');

        $response = new Response('redirect', 302);
        $this->responseFactory->expects($this->once())
            ->method('redirect')
            ->willReturn($response);

        $this->assertSame($response, $this->controller->togglePublish($request));
        $this->assertEquals(1, $blog->published);
    }
}
