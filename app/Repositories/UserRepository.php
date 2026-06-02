<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected string $className = User::class;
    protected string $tableName = "users";

    public function findByEmail(string $email): ?User
    {
        $email = strtolower(trim($email));

        $row = $this->database->run(
            "SELECT users.*, roles.name as role_name 
             FROM {$this->tableName} 
             LEFT JOIN roles ON users.role_id = roles.id 
             WHERE LOWER(users.email) = :email",
            ['email' => $email]
        )->fetch();

        if (!$row) {
            return null;
        }

        $user = $this->fromDBRow($row);
        return $user instanceof User ? $user : null;
    }
}
