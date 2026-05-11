<?php

namespace App\Models;

class Blog
{
    public int $id;
    public string $title;
    public string $subtitle;
    public string $slug;
    public string $content;
    public ?string $preview_image;
    public ?string $header_image;
    public string $created_at;
}
