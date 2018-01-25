<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Example;
use App\Feature;
use App\User;
use Auth;

class ExampleController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        return view('example');
    }


    /*
     * get all examples
     */

    public function fetchExamples() {
        $list = new \stdClass;

        $list->examples = Example::with('feature')->get();
        return response()->json($list);

    }


    public function analyse($code=NULL) {

        $result = new \stdClass;
        $details = array();
        $user_id = Auth::user()->id;
        $result->isAdmin = User::find($user_id)->hasRole('admin');

        if(isset($code)) {
           $details = Example::where('id', $code)->with('feature')->get();
        }
        $result->details = $details;

        return view('example.analyse', ['data' => $result]);
    }

    public function store(Request $request) {

        $this->validate(request(), [
            'txt' => 'required',
            'feedback' =>'required'
        ]);

        $status =array('success' => false, 'message' => 'Problem storing example');
        $code = 500;


        $example = new Example();

        $example->feature_id = $request['extra']['feature'];
        $example->title= $request['other']['title'];
        $example->summary = $request['other']['summary'];
        $example->faculty = $request['other']['faculty'];
        $example->text_input = $request['txt'];
        $example->raw_response = json_encode($request['feedback']);

        $example->save();

        if($example->id  >0 ) {
            $status['message'] = 'Example text stored';
            $status['success'] = true;
            $code = 200;
        }

        return response()->json($status, $code);

    }





}
