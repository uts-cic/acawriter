<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StringTokenizer extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');

    }


    public function process(Request $request) {
        $textLog = $request['txt'];
        if(count($textLog) > 30 && count($textLog) < 60) {
            $collection = collect($textLog);
            $unique = $collection->unique();
            $unique->values()->all();
            dd($unique);
        }

    }


}
