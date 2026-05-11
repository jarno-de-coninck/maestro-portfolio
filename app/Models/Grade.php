<?php

namespace App\Models;

class Grade
{
    public int $id;
    public string $course_name;
    public string $test_name;
    public float $ec;
    public string $quarter;
    public float $grade;
}
