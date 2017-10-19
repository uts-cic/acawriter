<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Assignment;


class AssignmentController extends Controller
{

    //this page has to be restricted to user_schema 'role' only
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        //get all assignments belonging to the user
        $assignments = User::find(Auth::user()->id)->assignments;
        return view('assignment', ['assignments' => $assignments]);
    }

    public function store(Request $request) {

        $this->validate(request(), [
            'name' => 'required'
            ]);

        $assignment = new Assignment();
        $assignment->name = $request->name;
        $assignment->code = str_random(8);
        $assignment->user_id = Auth::user()->id;
        $assignment->published =0;

        $assignment->save();

        return view('/assignment');
    }

}
