<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();

        User::truncate();
        $user = User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'name' => 'Administrator',
        ]);

        DB::table('role_user')->truncate();
        $user->roles()->attach($role_admin);
    }
}
