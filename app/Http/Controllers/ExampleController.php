<?php
/**
 * Copyright (c) 2018 original UTS CIC. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * UTS Connected Intelligence Centre
 */

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

        $list->examples = Example::where('hide',0)->with('feature')->get();
        return response()->json($list);

    }


    public function analyse($code=NULL) {

        $result = new \stdClass;
        $details = array();
        $user_id = Auth::user()->id;
        $result->isAdmin = User::find($user_id)->hasRole('admin');
        $features_all = Feature::all();
        $features = new \stdClass();

        foreach($features_all as $feature) {
            if(!isset($features->{$feature->grammar})) {
                $features->{$feature->grammar} = array();
            }
            $tmp = new \stdClass();
            $tmp->id = $feature->id; $tmp->name = $feature->name;
            array_push($features->{$feature->grammar}, $tmp );
        }
        $result->features = json_encode($features);

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
        $example->hide = 0;

        $example->save();

        if($example->id  >0 ) {
            $status['message'] = 'Example text stored';
            $status['success'] = true;
            $code = 200;
        }

        return response()->json($status, $code);

    }
}
