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

    //this page has to be restricted to user_schema 'role' only
    public function __construct()
    {
        $this->middleware('auth');
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
        //$features_all = Feature::all();
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

    public function store(Request $request)
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

        //return view('/assignment');
        return redirect()->back()->with('success', 'Assignment added successfully!');
    }

    public function subscribe(Request $request)
    {
        $this->validate(request(), [
            'assignment_code' => 'required'
        ]);

        $code = trim(htmlspecialchars($request['assignment_code'], ENT_QUOTES, 'UTF-8'));

        $user_id = Auth::user()->id;

        $assignments = Assignment::where('code', 'ILIKE', $code)->get();
        if (!$assignments->isEmpty()) {
            $assignment = $assignments->first();

            if (!is_null($assignment->example_id)) {
                $example = Example::find($assignment->example_id);
            }

            //skip insert if already subscribed
            $subscribed = DB::table('user_subscription')->where([
                ['user_id', '=', $user_id],
                ['assignment_id', '=', $assignment->id]
            ])->count();

            if (!$subscribed) {
                $subscribed = DB::table('user_subscription')->insert([
                    [
                        'user_id' => $user_id,
                        'assignment_id' => $assignment->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ]);
            }

            $document = $this->addDocument($assignment);

            if (isset($example) && $document) {
                $draft = $this->addDraft($example, $document, $assignment);
            }

            if (!$subscribed || !$document || isset($example) && !isset($draft)) {
                return redirect()->back()->with('error', 'Sorry, the document could not be created. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'Sorry, assignment code <strong>' . $code . '</strong> could not be found.');
        }

        return redirect('/analyse/' . $document->slug);
    }


    public function action(Request $request)
    {
        $complete = false;
        $status = array('success' => false, 'message' => 'Assignment could not be deleted');
        $code = 500;
        if ($request->action == 'delete') {
            if (is_numeric($request->id)) {
                //$res = Draft::where('assignment_id', $request->id)->delete();
                $complete = Assignment::where('id', $request->id)->delete();
                if ($complete) {
                    $status = array('success' => true, 'message' => 'Assignment is successfully deleted.');
                    $code = 200;
                }
            }
        }
        return response()->json($status, $code);
    }


    private function addDocument($assignment)
    {
        $document = new Document();
        $document->name = $assignment->name;
        $document->user_id = Auth::user()->id;
        $document->slug = strtolower(Str::random(22));
        $document->assignment_id = $assignment->id;
        $document->created_at = date('Y-m-d H:i:s');
        $document->updated_at = date('Y-m-d H:i:s');

        $document->save();

        return $document->id > 0 ? $document : null;
    }

    /**
     * called iff example_id is assignment_id is
     * @param $data example
     * @param $id document_id
     * @param $assignment assignment
     * @return draft_id
     */
    private function addDraft($data, $document, $assignment)
    {
        $draft = new Draft();
        $draft->text_input = $data->text_input;
        $draft->feature_id = $assignment->feature_id;
        $draft->document_id = $document->id;
        $draft->raw_response = $data->raw_response; //already json_decoded
        $draft->user_id = Auth::user()->id;
        $draft->is_auto = 0;
        $draft->created_at = date('Y-m-d H:i:s');
        $draft->updated_at = date('Y-m-d H:i:s');

        $draft->save();

        return $draft->id > 0 ? $draft : null;
    }
}
