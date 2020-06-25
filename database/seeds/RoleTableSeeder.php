<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        $role = new Role();
        $role->name = 'admin';
        $role->save();
        $role = new Role();
        $role->name = 'user';
        $role->save();
    }
}
