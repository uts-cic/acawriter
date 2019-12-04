<?php

namespace App\Http\Controllers;

use App\Document;
use App\Draft;
use App\Feature;
use Auth;
use App\TextDraft;

class AnalyseController extends Controller
{
    public $ui;

    //this page has to be restricted to user_schema 'role' only
    public function __construct()
    {
        $this->middleware('auth');
        $this->ui = new \stdClass;
        $this->ui->assignment_id = 0;
    }

    public function index($code = NULL)
    {
        $user_id = Auth::user()->id;
        $td = new TextDraft();
        if (isset($code)) {
            $this->ui->document = Document::where('slug', '=', $code)
                ->where('user_id', $user_id)
                ->with('assignment')
                ->get();

            if (count($this->ui->document) == 0) {
                return redirect('home');
            }
            $this->ui->document[0]->feature = Feature::where('id', $this->ui->document[0]->assignment->feature_id)->get();

            //$this->ui->document[0]->draft = Draft::where('document_id',$this->ui->document[0]->id)->orderBy('created_at','desc')->first();
            /* $this->ui->assignment = Assignment::where('code', '=', $code)
                                    ->with('feature')
                                    ->get();
            $this->ui->assignment_id = Assignment::where('code', '=', $code)->pluck('id');
            */

            $draft = Draft::where('document_id', $this->ui->document[0]->id)->orderBy('created_at', 'desc')->first();
            $textDraft = $td::where('document_id', $this->ui->document[0]->id)->orderBy('created_at', 'desc')->first();

            if (isset($textDraft) && isset($draft)) {
                if (strtotime($textDraft->created_at) > strtotime($draft->created_at)) {
                    $this->ui->document[0]->textDraft = $textDraft;
                } else {
                    $this->ui->document[0]->draft = $draft;
                }
            } else if (isset($textDraft)) {
                $this->ui->document[0]->textDraft = $textDraft;
            } else {
                $this->ui->document[0]->draft = $draft;
            }
        }

        return view('analyse', ['data' => $this->ui]);
    }
}
