<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Crm;
use Illuminate\Support\Facades\Mail;
use App\Mail\contactMailer;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    public function page($which)
    {
        if (!isset($which)) {
            return view('welcome');
        } elseif ($which === 'about') {
            return view('about');
        } elseif ($which === 'contact') {
            $pre_fill = new \stdClass();
            $pre_fill->name = '';
            $pre_fill->email = '';
            if (Auth::guest()) { } else {
                $pre_fill->name = Auth::user()->name;
                $pre_fill->email = Auth::user()->email;
            }
            return view('contact', ['contact' => $pre_fill]);
        } elseif ($which === 'terms') {
            return view('terms');
        } else {
            return view('welcome');
        }
    }

    public function storeContact(Request $request)
    {
        $this->validate($request, [
            'name'      =>  'required',
            'email'     =>  'required|email',
            'comment'   =>  'required',
            'captcha'   => 'required|captcha'
        ]);

        $crm = new Crm();
        $crm->name      = $request->name;
        $crm->email     = $request->email;
        $crm->comment   = $request->comment;

        if (Auth::guest()) { } else {
            $crm->user_id = Auth::user()->id;
        }

        $crm->ref = '/page/contact';

        $crm->save();

        Mail::to('cic-acawriter@uts.edu.au')->send(new contactMailer($crm));
        return redirect()->back()->with('success', 'Thanks for your feedback.');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
