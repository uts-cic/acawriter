<?php

namespace App\Http\Controllers;

use App\Assignment;
use Illuminate\Http\Request;


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
            $this->ui->assignment_id = Assignment::where('code', '=', $code)->pluck('id');
        }
      //  dd($this->ui);
        return view('analyse', ['data' => $this->ui]);
    }

}
