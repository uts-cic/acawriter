<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use App\Events\UserRegistered;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:administer-users']);
    }

    public function showUsers()
    {
        $input = $this->validate(request(), [
            'search' => ['string', 'max:255'],
        ]);
        $search = isset($input['search']) ? filter_var($input['search'], FILTER_SANITIZE_STRING) : '';
        $users = $search ? User::whereLike(['name', 'email'], $search)->paginate(100) : User::paginate(100);
        $roles = Role::all();
        return view('admin.user', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    public function updateUserRoles()
    {
        $input = $this->validate(request(), [
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $user = User::find($input['user_id']);
        $user->roles()->sync($input['roles']);

        return redirect()->back()->with('success', 'Roles updated successfully!');;
    }

    public function addUser()
    {
        $input = $this->validate(request(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:10', 'strong_password'],
        ]);

        $input['role'] = 'user';
        $user = $this->create($input);
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
