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
    // Show list of users
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

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
    // Return edit form for a user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user); // Return user data in JSON for AJAX
    }
     // Update user
     // app/Http/Controllers/UserController.php
public function update(Request $request, $id)
{
    // Fetch the user record
    $user = User::findOrFail($id);

    // Validate the request
    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);  // 422 status for validation errors
    }

    // Update user details
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return response()->json(['status' => 'success', 'success' => 'User updated successfully']);
}


     // Delete user
     public function destroy($id)
     {
         User::findOrFail($id)->delete();
         return response()->json(['success' => 'User deleted successfully']);
     }
}
