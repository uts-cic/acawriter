<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Assignment;
use App\Draft;
use Illuminate\Support\Facades\DB;
use App\Feature;
use App\Document;
use App\Example;


class AssignmentController extends Controller
{

    //this page has to be restricted to user_schema 'role' only
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        //get all assignments belonging to the user
        $assignments = User::find(Auth::user()->id)->assignments()->with('feature')->get();
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
        return view('assignment', ['assignments' => $assignments, 'features'=>$features]);
    }

    public function store(Request $request) {

        $this->validate(request(), [
            'name' => 'required',
            'grammar' =>'required'
            ]);

        $assignment = new Assignment();
        $assignment->name = $request->name;
        $assignment->feature_id=$request->grammar;
        $assignment->code = str_random(8);
        $assignment->user_id = Auth::user()->id;
        $assignment->keywords=$request->keywords;
        $assignment->published =0;

        $assignment->save();

        //return view('/assignment');
        return redirect()->back()->with('success','Assignment added successfully!');
    }

    public function search(Request $request) {

        $s = $request->input('query');
        $list = Assignment::where('code', 'ILIKE', '%'.$s.'%')->get();

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

        $document_id =0;

        $user_id = Auth::user()->id;
        if(count($request["list"]) > 0) {
            foreach($request["list"] as $a ) {
                $message = 'Document list updated';
                $up=array();
                $status =array('success' => true, 'message' => 'Added to your document list ');
                $code = 200;
                //skip insert if already subscribed
                $subscribed = DB::table('user_subscription')->where([
                    ['user_id', '=', $user_id],
                    ['assignment_id', '=', $a["id"]]
                ])->count();
                $selected_assignment = Assignment::find($a["id"]);

                if(!is_null($selected_assignment->example_id)) {
                    $example = Example::find($selected_assignment->example_id);
                }

                if($subscribed == 0) {
                    if(DB::table('user_subscription')->insert([
                        [
                            'user_id' => $user_id,
                            'assignment_id' => $a["id"],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]
                    ])) {
                        $document_id = $this->addDocument($a);
                        $up[]  = $document_id > 0 ? 0 :1 ;
                        if(isset($example) && $document_id > 0) {
                            $up[] = $this->addDraft($example, $document_id, $selected_assignment) > 0 ? 0 :1;
                        }
                    } else {
                        $up[]=1;
                    }
                } else {
                    $document_id = $this->addDocument($a);
                    $up[]  = $document_id > 0 ? 0 :1 ;
                    if(isset($example) && $document_id > 0) {
                        $up[] = $this->addDraft($example, $document_id, $selected_assignment) > 0 ? 0 :1;
                    }
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


    public function action(Request $request) {
        $complete = false;
        $status =array('success' => true, 'message' => 'Deleted Assignment');
        $code = 200;
        if($request->action == 'delete') {
            if(is_numeric($request->id) ) {

                    $res = Draft::where('assignment_id', $request->id)->delete();

                    $complete = Assignment::where('id', $request->id)->delete();

            }
        }
        return response()->json($status, $code);

    }


    private function addDocument($data) {

        $document = new Document();
        $document->name = $data['doc_name'];
        $document->user_id = Auth::user()->id;
        $document->slug = $data['doc_file'];
        $document->assignment_id = $data['id'];
        $document->created_at = date('Y-m-d H:i:s');
        $document->updated_at = date('Y-m-d H:i:s');

        $document->save();

        return $document->id > 0 ? $document->id : 0;
    }

    /**
     * called iff example_id is assignment_id is
     * @param $data example
     * @param $id document_id
     * @param $assignment assignment
     * @return draft_id
     */



    private function addDraft($data, $id, $assignment) {


        $draft = new Draft();
        $draft->text_input = $data->text_input;
        $draft->feature_id = $assignment->feature_id;
        $draft->document_id = $id;
        $draft->raw_response = $data->raw_response; //already json_decoded
        $draft->user_id = Auth::user()->id;
        $draft->is_auto = 0;
        $draft->created_at = date('Y-m-d H:i:s');
        $draft->updated_at = date('Y-m-d H:i:s');

        $draft->save();

        return $draft->id > 0 ? $draft->id : 0;
    }




}
