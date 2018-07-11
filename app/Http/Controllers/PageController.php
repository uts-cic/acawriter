<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Crm;
use Illuminate\Support\Facades\Mail;
Use App\Mail\contactMailer;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    //

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    public function page ($which) {
        if(!isset($which)) {
            return view ('welcome');
        } elseif($which==='about') {
            return view('about');
        } elseif($which==='contact') {
            return view('contact');
        }  elseif($which==='terms') {
            return view('terms');
        } else {
            return view ('welcome');
        }
    }

    public function storeContact(Request $request) {
        $this->validate($request, [
            'name'      =>  'required',
            'email'     =>  'required|email',
            'comment'   =>  'required'
        ]);

        $crm = new Crm();

        $crm->name      = $request->name;
        $crm->email     = $request->email;
        $crm->comment   = $request->comment;



        if (Auth::guest()) {

        } else {
            $crm->user_id=Auth::user()->id;
        }


        $crm->ref = '/page/contact';

        $crm->save();


        /*Mail::send('email',
            array(
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'comment' => $request->get('comment')
            ), function($message)
            {
                $message->from('cicsysadmin@uts.edu.au');
                $message->to('cicsysadmin@uts.edu.au', 'Admin')->subject('AcaWriter notification');
            });*/

            Mail::to('cicsysadmin@uts.edu.au')->send(new contactMailer($crm));




        return redirect()->back()->with('success','Thanks for your feedback.');


    }

}
