<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $userData;


    public function __construct()
    {
        $this->middleware('auth');
        $this->userData = new \stdClass;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $roles = Auth::user()->roles;
        $this->userData->roles = array();
        foreach($roles as $role) {
            $this->userData->roles[] = $role->name;
        }

        return view('home', ['data'=> $this->userData]);
    }
}
