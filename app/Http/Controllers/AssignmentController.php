<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Assignment;
use Illuminate\Support\Facades\DB;


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

    public function search(Request $request) {

        $s = $request->input('query');
        $list = Assignment::where('name', 'ILIKE', '%'.$s.'%')->get();

        return $list;
    }


    /*
     * user_subscriber table
     * holds many to many relation between assignments and users
     * input list:array() - assignments
     */

    public function subscribeUserToAssignment(Request $request) {

        $this->validate(request(), [
            'list' => 'required'
        ]);


        $user_id = Auth::user()->id;
        if(count($request["list"]) > 0) {
            foreach($request["list"] as $a ) {
                $message = 'Record Updated';
                $up=array();
                $status =array('success' => true, 'message' => 'Record Updated');
                $code = 200;
                //skip insert if already subscribed
                $subscribed = DB::table('user_subscription')->where([
                    ['user_id', '=', $user_id],
                    ['assignment_id', '=', $a["id"]]
                ])->count();

                if($subscribed == 0) {
                    if(DB::table('user_subscription')->insert([
                        [
                            'user_id' => $user_id,
                            'assignment_id' => $a["id"],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]
                    ])) {
                          $up[] =0;
                    } else {
                        $up[]=1;
                    }
                } else {
                    $up[] =0;
                }
            }
            if(in_array(1, $up)) {
                $status['success'] = false;
                $status['message'] = "Some error while saving";
                $code = 422;
            }
        }

        return response()->json($status, $code);
    }




}
