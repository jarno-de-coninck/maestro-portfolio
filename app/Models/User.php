<?php

namespace App\Models;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public ?int $role_id;
    public ?string $role_name = null;
    public ?Role $role = null;
}
