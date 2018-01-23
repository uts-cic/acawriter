<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Example;
use App\Feature;

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

        $list->examples = Example::with('feature');
        return response()->json($list);

    }


    public function analyse($code=NULL) {

        $details = array();

        if(isset($code)) {
           $details = Example::where('id', $code)->with('feature')->get();
        }

        return view('example.analyse', ['data' => $details]);
    }





}
