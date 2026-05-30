<?php

namespace App\Models;

use DateTime;

class Profile
{
    public ?int $id = null;
    public string $name = '';
    public string $birthdate = '';
    public string $bio = '';
    public string $characteristics = '';
    public string $skills = '';
    public string $linkedin_url = '';
    public string $github_url = '';

    public function getAge(): int
    {
        $date = new DateTime($this->birthdate);
        return $date->diff(new DateTime())->y;
    }
}
