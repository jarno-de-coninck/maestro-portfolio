<?php

namespace App\Repositories;

use App\Models\Grade;
use Framework\Database;

class GradeRepository extends AbstractRepository implements GradeRepositoryInterface
{
    protected string $tableName = 'grades';
    protected string $className = Grade::class;

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }
}
