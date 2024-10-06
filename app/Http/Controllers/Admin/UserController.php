<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Show the form for creating a new user
    public function create()
    {
        // return 1;
        $roles = Role::all(); // Get all roles to display in the form
        return view('admin.users.create', compact('roles'));
    }

    // Store a newly created user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array', // Ensure at least one role is selected
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attach the selected roles to the user
        $user->roles()->attach($request->roles);

        return redirect()->route('admin.users.create')->with('success', 'User created successfully!');
    }
}
