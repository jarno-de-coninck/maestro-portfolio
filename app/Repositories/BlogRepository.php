<?php

namespace App\Repositories;

use App\Models\Blog;
use Framework\Database;

class BlogRepository extends AbstractRepository implements BlogRepositoryInterface
{
    protected string $tableName = 'blogs';
    protected string $className = Blog::class;

    public function findBySlug(string $slug): ?Blog
    {
        $row = $this->database->run("SELECT * FROM {$this->tableName} WHERE slug = :slug", ["slug" => $slug])->fetch();

        if (!$row) {
            return null;
        }

        /**
         * @var Blog
         */
        return $this->fromDBRow($row);
    }
}
