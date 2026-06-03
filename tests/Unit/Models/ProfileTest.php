<?php

namespace Tests\Unit\Models;

use App\Models\Profile;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    public function testInstantiation(): void
    {
        $profile = new Profile();
        $profile->name = 'Jarno';
        $profile->bio = 'Developer';

        $this->assertEquals('Jarno', $profile->name);
        $this->assertEquals('Developer', $profile->bio);
    }
}
