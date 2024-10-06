<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class RoleController extends Controller
{
    public function assignRole($userId, $roleName)
{
    // Find the user by their ID
    $user = User::find($userId);

    // Find the role by its name
    $role = Role::where('name', $roleName)->first();

    // Assign the role to the user
    $user->roles()->attach($role->id);

    return "Role '{$roleName}' assigned to user with ID {$userId}";
}
}
