<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Auth;

use App\User;
use App\Assignment;
use App\Draft;
use App\Feature;
use App\Document;
use App\Example;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-assignments']);
    }

    public function index()
    {
        //get all assignments belonging to the user
        $assignments = User::find(Auth::user()->id)
            ->assignments()
            ->where('name', '!=', 'NA')
            ->with('feature')
            ->orderBy('created_at', 'desc')
            ->get();

        $features_all = Feature::orderBy('id')->get();
        $features = new \stdClass();

        foreach ($features_all as $feature) {
            if (!isset($features->{$feature->grammar})) {
                $features->{$feature->grammar} = array();
            }
            $tmp = new \stdClass();
            $tmp->id = $feature->id;
            $tmp->name = $feature->name;
            $tmp->info = $feature->info;
            array_push($features->{$feature->grammar}, $tmp);
        }
        return view('assignment', ['assignments' => $assignments, 'features' => $features]);
    }

    public function create(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'grammar' => 'required'
        ]);

        $assignment = new Assignment();
        $assignment->name = $request->name;
        $assignment->feature_id = $request->grammar;
        $assignment->code = Str::random(8);
        $assignment->user_id = Auth::user()->id;
        $assignment->keywords = $request->keywords;
        $assignment->published = 0;

        $assignment->save();

        return redirect()->back()->with('success', 'Assignment added successfully!');
    }

    public function delete(Request $request)
    {
        $id = !empty($request->id) ? intval($request->id) : null;
        $user_id = Auth::user()->id;

        $success = $id && Assignment::where('id', $id)->where('user_id', $user_id)->delete();

        if ($success) {
            $response = array('success' => true, 'message' => 'Assignment is successfully deleted.');
        }
        else {
            $response = array('success' => false, 'message' => 'Assignment could not be deleted');
        }

        return response()->json($response, 200);
    }

}
