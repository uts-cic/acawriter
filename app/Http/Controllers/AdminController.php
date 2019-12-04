<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Events\UserRegistered;

class AdminController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showUsers()
    {
        $data = new \stdClass;
        $users = User::where('email', 'like', '%@uts.edu.au')->paginate(50);
        $data->users = $users;
        $roles = Role::all();
        $data->roles = $roles;
        return view('admin.user', ['data' => $data]);
    }

    public function updateUserRoles(Request $request)
    {
        $roles = array();
        if (isset($request["roles"])) {
            $roles = $request["roles"];
        }
        $user = User::find($request["user_id"]);
        $user->roles()->sync($roles);

        return redirect()->back()->with('success', 'Roles updated successfully!');;
    }

    public function addUser(Request $request)
    {
        if ($this->userExists($request)) {
            return redirect()->back()->with('error', 'User already exists');
        }

        $credentials = array(
            'email' => $request["new_email"],
            'name' => $request["new_name"],
            'password' => $request["new_password"]
        );
        $whatRole = 'user';
        $userAdded = $this->create($credentials);
        event(new UserRegistered($userAdded, $whatRole));

        return redirect()->back()->with('success', 'User added successfully!');
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


    protected function userExists($data)
    {
        $userFound = User::where('email', $data["new_email"])->first();
        if ($userFound) {
            return true;
        }
        return false;
    }
}
