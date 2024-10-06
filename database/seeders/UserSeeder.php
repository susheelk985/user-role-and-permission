<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create an admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // Create a user role if it doesn't exist
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create a default admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('admin123'), // Set a default password
        ]);

        // Assign the admin role to the user
        $admin->roles()->attach($adminRole);

        // Create a default regular user
        $user = User::firstOrCreate([
            'email' => 'user@gmail.com'
        ], [
            'name' => 'Regular User',
            'password' => Hash::make('user123'), // Set a default password
        ]);

        // Assign the user role to the user
        $user->roles()->attach($userRole);
    }
}
