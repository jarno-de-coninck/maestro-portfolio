<?php

namespace App\Repositories;

use App\Models\Role;
use Framework\Database;

class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    protected string $tableName = 'roles';
    protected string $className = Role::class;

    /** @return Role[] */
    public function all(): array
    {
        $rows = $this->database->run("SELECT * FROM roles")->fetchAll();
        $roles = array_map(fn($row) => $this->fromDBRow($row), $rows);

        $validRoles = [];
        foreach ($roles as $role) {
            if ($role instanceof Role) {
                $validRoles[] = $role;
            }
        }
        return $validRoles;
    }

    public function findById(int $id): ?Role
    {
        $row = $this->database->run(
            "SELECT * FROM roles WHERE id = :id",
            ['id' => $id]
        )->fetch();

        if (!$row) {
            return null;
        }

        $role = clone $this->fromDBRow($row);
        if ($role instanceof Role) {
            return $role;
        }
        return null;
    }

    public function findByName(string $name): ?Role
    {
        $row = $this->database->run(
            "SELECT * FROM roles WHERE name = :name",
            ['name' => $name]
        )->fetch();

        if (!$row) {
            return null;
        }

        $role = clone $this->fromDBRow($row);
        if ($role instanceof Role) {
            return $role;
        }
        return null;
    }
}
