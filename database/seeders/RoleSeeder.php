<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $roles =[
        [
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'teacher',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'student',
            'created_at' => now(),
            'updated_at' => now()
        ],
    ];

    foreach ($roles as $role) {
        Role::query()->firstOrCreate(
            ['name' => $role['name']],
            $role
        );
    }

    }
}
