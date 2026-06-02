<?php

namespace App\Models;

class Role
{
    public const ADMIN = 'admin';
    public const USER = 'user';

    public int $id;
    public string $name;
}
