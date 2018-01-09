<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Assignment;


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
        //roles
        $roles = Auth::user()->roles;
        $this->userData->roles = array();
        foreach($roles as $role) {
            $this->userData->roles[] = $role->name;
        }

        //assignments
        $this->userData->assignments= array();
        $user_id = Auth::user()->id;
        $list = DB::table('user_subscription')
            ->select('assignment_id')
            ->where('user_id','=',$user_id)
            ->get();
       //dd($list);
        if(count($list) > 0 ) {
            foreach($list as $a) {
               //print_r($a->assignment_id);
                $this->userData->assignments = Assignment::where('id',$a->assignment_id)->with('feature')->get();
            }
        }
//dd($this->userData->assignments);
        return view('home', ['data'=> $this->userData]);
    }

}
