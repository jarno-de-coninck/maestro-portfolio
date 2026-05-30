<?php

namespace App\Repositories;

use App\Models\Profile;
use Framework\Database;

class ProfileRepository extends AbstractRepository implements ProfileRepositoryInterface
{
    protected string $tableName = 'profiles';
    protected string $className = Profile::class;

    public function get(): ?Profile
    {
        $profile = $this->findById(1);
        if ($profile instanceof Profile) {
            return $profile;
        }
        return null;
    }
}
