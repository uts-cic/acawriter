<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\User;
use App\Role;

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

}
