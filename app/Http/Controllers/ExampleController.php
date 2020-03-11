<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Example;
use App\Feature;
use App\User;
use Auth;

class ExampleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:view-examples']);
    }

    public function index()
    {
        return view('example');
    }

    /*
     * get all examples
     */
    public function fetchExamples()
    {
        $list = new \stdClass;

        $list->examples = Example::where('hide', 0)->with('feature')->get();
        return response()->json($list);
    }

    public function analyse($code = NULL)
    {
        $result = new \stdClass;
        $details = array();
        $user_id = Auth::user()->id;
        $result->isAdmin = User::find($user_id)->hasRole('admin');
        $features_all = Feature::all();
        $features = new \stdClass();

        foreach ($features_all as $feature) {
            if (!isset($features->{$feature->grammar})) {
                $features->{$feature->grammar} = array();
            }
            $tmp = new \stdClass();
            $tmp->id = $feature->id;
            $tmp->name = $feature->name;
            array_push($features->{$feature->grammar}, $tmp);
        }
        $result->features = json_encode($features);

        if (isset($code)) {
            $details = Example::where('id', $code)->with('feature')->get();
        }
        $result->details = $details;


        return view('example.analyse', ['data' => $result]);
    }
}
