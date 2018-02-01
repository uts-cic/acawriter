<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function page ($which) {
        if(!isset($which)) {
            return view ('welcome');
        } elseif($which==='about') {
            return view('about');
        } elseif($which==='contact') {
            return view('contact');
        } else {
            return view ('welcome');
        }
    }
}
