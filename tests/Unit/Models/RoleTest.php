<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testConstantsAndInstantiation(): void
    {
        $this->assertEquals('admin', Role::ADMIN);
        $this->assertEquals('user', Role::USER);

        $role = new Role();
        $role->name = 'test_role';
        
        $this->assertEquals('test_role', $role->name);
    }
}
