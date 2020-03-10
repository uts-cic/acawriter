<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Role;
use App\Events\UserRegistered;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-users']);
    }

    public function showUsers(Request $request)
    {
        $search = $request->input('search');
        $users = $search ? User::whereLike(['name', 'email'], $search)->paginate(100) : User::paginate(100);
        $roles = Role::all();
        return view('admin.user', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
        ]);
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
        $validator = Validator::make(Input::all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            return redirect()->back()->with('error', implode("\n", $messages));
        }

        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $role = 'user';

        $user = $this->create([
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'role' => $role
        ]);

        return redirect()->back()->with('success', 'User added successfully!');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $params
     * @return \App\User
     */
    protected function create(array $params)
    {
        $user =  User::create([
            'name' => $params['name'],
            'email' => $params['email'],
            'password' => Hash::make($params['password']),
        ]);

        event(new UserRegistered($user, $params['role']));

        return $user;
    }

}
