<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInstantiation(): void
    {
        $user = new User();
        $user->name = 'Barry';
        $user->email = 'barry@hz.nl';

        $this->assertEquals('Barry', $user->name);
        $this->assertEquals('barry@hz.nl', $user->email);
        $this->assertNull($user->role_name);
    }
}
