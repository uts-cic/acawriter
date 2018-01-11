<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Assignment;
use App\Document;
use App\Draft;

class DocumentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request) {

        $this->validate(request(), [
            'docu_name' => 'required',
            'doc_grammar' =>'required'
        ]);
        $up = array();

        $assignment = new Assignment();
        $assignment->name = 'NA';
        $assignment->feature_id=$request->doc_grammar;
        $assignment->code = str_random(8);
        $assignment->user_id = Auth::user()->id;
        $assignment->keywords='';
        $assignment->published =0;
        $assignment->updated_at = date('Y-m-d H:i:s');
        $assignment->created_at = date('Y-m-d H:i:s');

        $assignment->save();

        if($assignment->id > 0) {
            $up[] = 0;

            $subscribed = DB::table('user_subscription')->where([
                ['user_id', '=', Auth::user()->id],
                ['assignment_id', '=', $assignment->id]
            ])->count();

            if($subscribed == 0) {
                if (DB::table('user_subscription')->insert([
                    [
                        'user_id' => Auth::user()->id,
                        'assignment_id' => $assignment->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                ])) {
                    $up[] = 0;
                } else {
                    $up[] =1;
                }
            }

            $document = new Document();
            $document->name = $request->docu_name;
            $document->user_id = Auth::user()->id;
            $document->slug = strtolower(str_random(22));
            $document->assignment_id = $assignment->id;
            $document->created_at = date('Y-m-d H:i:s');
            $document->updated_at = date('Y-m-d H:i:s');

            $document->save();
            $up[] = $document->id > 0 ? 0: 1;
        } else {
            $up[] = 1;
        }

        if(in_array(1, $up)) {
            return redirect()->back()->with('error','Error creating document');
        }

        //return view('/assignment');
        return redirect()->back()->with('success','Document added successfully!');
    }


    public function action(Request $request) {

        $complete = false;
        $status =array('success' => true, 'message' => 'Deleted Document');
        $code = 200;
        if($request->action == 'delete') {
            if(is_numeric($request->id) ) {

                $res = Draft::where('document_id', $request->id)->delete();
                $complete = Document::where('id', $request->id)->delete();
            }
        }
        return response()->json($status, $code);

    }


    /*
     * get all documents for the user
     */

    public function fetchDocuments() {
        $list = new \stdClass;
        $list->documents= array();
        $assignments = array();

        $user_id = Auth::user()->id;


        $documentList = DB::table('user_subscription')
            ->select('assignment_id')
            ->where('user_id','=',$user_id)
            ->get();

        $assignmentIds = array();
        if(count($documentList) > 0 ) {
            foreach($documentList as $a) {
                $assignmentIds[]=  $a->assignment_id;
            }

            $assignments = Assignment::whereIn('id',$assignmentIds)->with('feature')->get();
            $list->documents = Document::whereIn('assignment_id',$assignmentIds)->with('assignment')->get();
        }

        if(count($list->documents) > 0 && count($assignments) >0) {
            foreach($list->documents as $document) {
                foreach($assignments as $assignment ) {
                    if($document->assignment_id === $assignment->id) {
                        $document->feature_id = $assignment->feature->id;
                        $document->grammar = $assignment->feature->grammar;
                    }
                }
            }
        }

        return response()->json($list);

    }





}
