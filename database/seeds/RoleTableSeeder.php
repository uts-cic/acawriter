<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array(
                'id' => 1,
                'name' => 'demo',
                'description' => 'Demo user'
            ),
            array(
                'id' => 2,
                'name' => 'user',
                'description' => 'Student'
            ),
            array(
                'id' => 3,
                'name' => 'staff',
                'description' => 'Staff'
            ),
            array(
                'id' => 4,
                'name' => 'admin',
                'description' => 'Administrator'
            ),
        );

        foreach ($roles as $data) {
            $role = Role::find($data['id']);
            if (!$role) {
                $role = new Role();
            }
            $role->id = $data['id'];
            $role->name = $data['name'];
            $role->description = $data['description'];
            $role->save();
        }
    }
}
