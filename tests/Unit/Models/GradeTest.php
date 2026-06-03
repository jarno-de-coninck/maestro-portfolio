<?php

namespace Tests\Unit\Models;

use App\Models\Grade;
use PHPUnit\Framework\TestCase;

class GradeTest extends TestCase
{
    public function testInstantiation(): void
    {
        $grade = new Grade();
        $grade->course_name = 'Object-Oriented Programming';
        $grade->test_name = 'Final Assignment';
        $grade->grade = 8.5;
        
        $this->assertEquals('Object-Oriented Programming', $grade->course_name);
        $this->assertEquals('Final Assignment', $grade->test_name);
        $this->assertEquals(8.5, $grade->grade);
    }
}
