<?php

namespace App\Repositories;

use App\Models\Blog;
use Framework\Database;

class BlogRepository extends AbstractRepository implements BlogRepositoryInterface
{
    protected string $tableName = 'blogs';
    protected string $className = Blog::class;

    public function __construct(Database $database)
    {
        parent::__construct($database);
    }

    public function all(): array
    {
        $rows = $this->database->run("SELECT * FROM {$this->tableName} ORDER BY created_at DESC")->fetchAll();
        $items = [];

        foreach ($rows as $row) {
            $items[] = $this->fromDBRow($row);
        }

        return $items;
    }

    public function findBySlug(string $slug): ?Blog
    {
        $row = $this->database->run("SELECT * FROM {$this->tableName} WHERE slug = :slug", ["slug" => $slug])->fetch();

        if (!$row) {
            return null;
        }

        return $this->fromDBRow($row);
    }
}
