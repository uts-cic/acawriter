<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Assignment;


class AssignmentController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        $assignments = Assignment::all();
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
