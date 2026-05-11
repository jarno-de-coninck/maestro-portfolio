<?php

namespace App\Repositories;

use Exception;
use Framework\Database;
use ReflectionProperty;

abstract class AbstractRepository
{
    protected Database $database;
    protected string $tableName;
    protected string $className;

    /** @var array<string> */
    protected array $columnNames;

    /**
     * @throws Exception
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->columnNames = $this->getColumnNames($this->tableName);
    }

    /**
     * @param string $tableName
     * @return array<string>
     * @throws Exception
     */
    private function getColumnNames(string $tableName): array
    {
        $query = "SELECT name FROM sqlite_master " .
            "WHERE type = 'table' AND name NOT LIKE 'sqlite_%';";

        $statement = $this->database->run($query);
        $tables = [];

        foreach ($statement as $row) {
            $tables[] = $row->name;
        }

        if (!in_array($tableName, $tables, true)) {
            throw new Exception("Table named $tableName does not exist");
        }

        $infoQuery = "SELECT name FROM pragma_table_info('$tableName')";
        $stmt = $this->database->run($infoQuery);

        $columnNames = [];
        foreach ($stmt as $row) {
            $columnNames[] = $row->name;
        }

        return $columnNames;
    }

    /**
     * @return array<object>
     */
    public function all(): array
    {
        $query = "SELECT * FROM {$this->tableName} ORDER BY id";
        $rows = $this->database->run($query)->fetchAll();

        $items = [];

        foreach ($rows as $row) {
            $items[] = $this->fromDBRow($row);
        }

        return $items;
    }

    protected function fromDBRow(object $row): object
    {
        $object = new $this->className();

        foreach ($this->columnNames as $column) {
            $object->{$column} = $row->{$column};
        }

        return $object;
    }

    public function findById(int $id): ?object
    {
        $query = "SELECT * FROM {$this->tableName} WHERE id = :id";
        $row = $this->database->run($query, ['id' => $id])->fetch();

        if (!$row) {
            return null;
        }

        return $this->fromDBRow($row);
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM {$this->tableName} WHERE id = :id";
        $this->database->run($query, ['id' => $id]);
    }

    public function update(object $entity): void
    {
        $setParts = [];
        $params = [];

        foreach ($this->columnNames as $column) {
            if ($column === 'id') {
                continue;
            }

            if (!property_exists($entity, $column)) {
                continue;
            }

            $reflection = new ReflectionProperty($entity, $column);
            if (!$reflection->isInitialized($entity)) {
                continue;
            }

            $setParts[] = "{$column} = :{$column}";
            $params[$column] = $entity->{$column};
        }

        /** @phpstan-ignore-next-line */
        $params['id'] = $entity->id;

        $sql = "UPDATE {$this->tableName} SET " . implode(', ', $setParts) .
            " WHERE id = :id";

        $this->database->run($sql, $params);
    }

    public function insert(object $entity): object
    {
        $columns = [];
        $placeholders = [];
        $params = [];

        foreach ($this->columnNames as $column) {
            if ($column === 'id') {
                continue;
            }

            if (!property_exists($entity, $column)) {
                continue;
            }

            $reflection = new ReflectionProperty($entity, $column);
            if (!$reflection->isInitialized($entity)) {
                continue;
            }

            $columns[] = $column;
            $placeholders[] = ':' . $column;
            $params[$column] = $entity->{$column};
        }

        $sql = "INSERT INTO {$this->tableName} (" . implode(', ', $columns) .
            ") VALUES (" . implode(', ', $placeholders) . ")";

        $this->database->run($sql, $params);

        /** @phpstan-ignore-next-line */
        $entity->id = (int) $this->database->getLastID();

        return $entity;
    }
}
