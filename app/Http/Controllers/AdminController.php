<?php
/**
 * Copyright (c) 2018 original UTS CIC. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * UTS Connected Intelligence Centre
 */



namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Events\UserRegistered;

class AdminController extends Controller
{
    public $data;
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUsers() {
        $data = new \stdClass;
        $users = User::all();
        $data->users = $users;
        $roles = Role::all();
        $data->roles = $roles;
        return view('admin.user', ['data' => $data]);
    }

    public function updateUserRoles(Request $request){
        $roles = array();
        if(isset($request["roles"])) {
            $roles = $request["roles"];
        }
        $user = User::find($request["user_id"]);
        $user->roles()->sync($roles);

        return redirect()->back()->with('success','Roles updated successfully!');;
    }

    public function addUser(Request $request){

        if($this->userExists($request)) {
            return redirect()->back()->with('error','User already exists');
        }


        $credentials = array(
                    'email' => $request["new_email"],
                    'name' => $request["new_name"],
                    'password' => $request["new_password"]
        );
        $whatRole = 'user';
        $userAdded = $this->create($credentials);
        event(new UserRegistered($userAdded,$whatRole));


        return redirect()->back()->with('success','User added successfully!');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $user;
    }


    protected function userExists($data) {
        $userFound = User::where('email', $data["new_email"])->first();
        if($userFound) {return true;}
        return false;
    }


}
