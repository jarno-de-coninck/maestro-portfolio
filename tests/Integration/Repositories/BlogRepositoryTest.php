<?php

namespace Tests\Integration\Repositories;

use App\Models\Blog;
use App\Repositories\BlogRepository;

use Tests\Integration\IntegrationTestCase;

class BlogRepositoryTest extends IntegrationTestCase
{
    private BlogRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new BlogRepository($this->database);
    }

    public function testFindBySlug(): void
    {
        $blog = $this->repository->findBySlug('first-feedback');
        
        $this->assertNotNull($blog);
        $this->assertEquals('First Feedback', $blog->title);
    }

    public function testFindBySlugReturnsNullIfNotFound(): void
    {
        $blog = $this->repository->findBySlug('non-existent-slug');
        $this->assertNull($blog);
    }

    public function testInsertAndFindById(): void
    {
        $blog = new Blog();
        $blog->title = 'HZ IT Portfolio Journey';
        $blog->subtitle = 'Test Subtitle';
        $blog->slug = 'test-blog';
        $blog->content = 'Test Content';
        $blog->published = 1;

        $insertedBlog = $this->repository->insert($blog);
        $this->assertIsInt($insertedBlog->id);

        $foundBlog = $this->repository->findById($insertedBlog->id);
        $this->assertNotNull($foundBlog);
        $this->assertEquals('HZ IT Portfolio Journey', $foundBlog->title);
    }
}
