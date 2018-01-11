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

        $user_id = Auth::user()->id;


        $documentList = DB::table('user_subscription')
            ->select('assignment_id')
            ->where('user_id','=',$user_id)
            ->get();
        //dd($list);
        if(count($documentList) > 0 ) {
            foreach($documentList as $a) {
                //print_r($a->assignment_id);
                $assignments = Assignment::where('id',$a->assignment_id)->with('feature')->get();
                $list->documents = Document::where('assignment_id',$a->assignment_id)->with('assignment')->get();
                foreach($list->documents as $document) {
                    foreach($assignments as $assignment ) {
                        if($document->assignment_id === $assignment->id) {
                            $document->feature_id = $assignment->feature->id;
                            $document->grammar = $assignment->feature->grammar;
                        }
                    }
                }
            }
        }

        return response()->json($list);

    }





}
