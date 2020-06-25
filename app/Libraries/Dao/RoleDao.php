<?php

namespace App\Libraries\Dao;

use App\Models\Role;

class RoleDao
{
    public function select()
    {
        $roles = Role::all();

        return $roles;
    }
}
