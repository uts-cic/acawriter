<?php
/**
 * Copyright (c) 2018 original UTS CIC. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * UTS Connected Intelligence Centre
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Feature;
use App\Document;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $userData;


    public function __construct()
    {
        $this->middleware('auth');
        $this->userData = new \stdClass;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //roles
        $roles = Auth::user()->roles;
        $this->userData->roles = array();
        foreach($roles as $role) {
            $this->userData->roles[] = $role->name;
        }
        $this->userData->features = Feature::all();

        return view('home', ['data'=> $this->userData]);
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
