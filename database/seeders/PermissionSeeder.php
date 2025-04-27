<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'id' => 1,
                'role_id' => 1,
                'name' => 'administrator',
                'permissions' => [
                    'role'       =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'permission' =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'admin'      =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'sponsor'    =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'category'   =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
//                  'user'       =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'teacher'    =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'student'    =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'game'       =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'word'       =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'level'      =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'image'      =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'audio'      =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                    'type'      =>  ['can-add'=>'1','can-edit'=>'1','can-delete'=>'1','can-show'=>'1'],
                ]
            ],
        ] ;
        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate($permission);

        }
    }
}
