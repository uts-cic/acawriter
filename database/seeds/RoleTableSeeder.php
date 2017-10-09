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
        //demo
        $role_demo = new Role();
        $role_demo->name ='demo';
        $role_demo->description = "Role for Students";
        $role_demo->save();

        //student
        $role_student = new Role();
        $role_student->name ='student';
        $role_student->description = "Role for Students";
        $role_student->save();

        //staff
        $role_staff = new Role();
        $role_staff->name ='staff';
        $role_staff->description = "Role for Staff";
        $role_staff->save();

        //admin
        $role_admin = new Role();
        $role_admin->name ='admin';
        $role_admin->description = "Role for Admin";
        $role_admin->save();




    }
}
