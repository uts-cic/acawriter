<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assignment;
use App\Document;
use App\Draft;
use App\Feature;


class AnalyseController extends Controller
{


    public $ui;

    //this page has to be restricted to user_schema 'role' only
    public function __construct() {
        $this->middleware('auth');
        $this->ui = new \stdClass;
        $this->ui->assignment_id=0;
    }


    public function index($code=NULL) {

        if(isset($code)) {

            $this->ui->document = Document::where('slug', '=', $code)->with('assignment')->get();

            $this->ui->document[0]->feature = Feature::where('id',$this->ui->document[0]->assignment->feature_id)->get();

            $this->ui->document[0]->draft = Draft::where('document_id',$this->ui->document[0]->id)->orderBy('created_at','desc')->first();
            /* $this->ui->assignment = Assignment::where('code', '=', $code)
                                    ->with('feature')
                                    ->get();
            $this->ui->assignment_id = Assignment::where('code', '=', $code)->pluck('id');
            */
        }

        return view('analyse', ['data' => $this->ui]);
    }

}
