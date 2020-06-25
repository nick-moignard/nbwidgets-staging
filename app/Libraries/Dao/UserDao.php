<?php

namespace App\Libraries\Dao;

use App\User;
use App\Models\Role;

class UserDao
{
    public function select()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('users.access', 1)
            ->select('users.id', 'users.name as user_name', 'users.email', 'roles.name as role_name')
            ->get();

        return $users;
    }
    public function insert($request, $role)
    {
        if (User::where('email', $request['email'])->exists()) {
            return response()->json(['status' => 'error', 'data' => 'Email has already been taken'], 200);
        }
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);
        $user
            ->roles()
            ->attach(Role::find($role));

        return response()->json(['status' => 'ok', 'data' => $user], 200);
    }

    public function get($id)
    {
        $user = User::where('id', $id)->where('access', '1')->get()->first();
        $user->role = $user->hasRole('admin')?1:1;

        return $user;
    }
    public function update($userRequest,$roleRequest)
    {
        $user = User::find($userRequest['id']);
        if (empty($user)) {
            return response()->json(['status' => 'error', 'data' => 'Email has already been taken'], 200);
        }
        $user->name = $userRequest['name'];
        $user->email = $userRequest['email'];
        if(isset($userRequest['password'])){
            $user->password = bcrypt($userRequest['password']);
        }
        $user->save();
        //dd($user->roles);
        $user
            ->roles()
            ->detach($user->roles[0]->id);
        $user
            ->roles()
            ->attach(Role::find($roleRequest));

        return response()->json(['status' => 'ok', 'data' => $user], 200);;
    }
    public function delete($id)
    {
        return User::find($id)->update(['access' => 0]);
    }
}
