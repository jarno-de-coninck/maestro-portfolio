<?php

namespace Tests\Unit\Models;

use App\Models\Blog;
use PHPUnit\Framework\TestCase;

class BlogTest extends TestCase
{
    public function testInstantiation(): void
    {
        $blog = new Blog();
        $blog->title = 'HZ HBO-ICT Experience';
        $blog->slug = 'hz-hbo-ict-experience';
        $blog->published = 1;

        $this->assertEquals('HZ HBO-ICT Experience', $blog->title);
        $this->assertEquals('hz-hbo-ict-experience', $blog->slug);
        $this->assertEquals(1, $blog->published);
    }
}
