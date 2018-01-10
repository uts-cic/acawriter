<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }




    public function action($action, $id) {
        if($action === 'delete') {

        }
    }
}
