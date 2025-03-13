<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'role_id' => 1,
                'first_name' => 'Admin',
                'last_name' => 'administrator',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
                'terms_and_conditions' => true
            ],
        ];
        foreach ($users as $user) {
            User::query()->firstOrCreate($user);
        }
    }
}
