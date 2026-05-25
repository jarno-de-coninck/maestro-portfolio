<?php

namespace App\Repositories;

use App\Models\Grade;

interface GradeRepositoryInterface
{
    /**
     * @return Grade[]
     */
    public function all(): array;
    public function findById(int $id): ?Object;
    public function update(Grade $grade): void;
}
