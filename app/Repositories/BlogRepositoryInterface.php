<?php

namespace App\Repositories;

use App\Models\Blog;

interface BlogRepositoryInterface
{
    /**
     * @return Blog[]
     */
    public function all(): array;
    public function findById(int $id): ?object;
    public function findBySlug(string $slug): ?Blog;
    public function insert(Blog $blog): object;
    public function update(Blog $blog): void;
    public function delete(int $id): void;
}
