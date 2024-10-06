<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();

        $createPost = Permission::create(['name' => 'create-post']);
        $editPost = Permission::create(['name' => 'edit-post']);
        $deletePost = Permission::create(['name' => 'delete-post']);

        $adminRole->permissions()->attach([$createPost->id, $editPost->id, $deletePost->id]);
        $editorRole->permissions()->attach([$createPost->id, $editPost->id]);
    }
}
