<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Crm;
use App\Mail\ContactMail;


class ContactController extends Controller
{

    public function index()
    {
        $contact = new \stdClass();
        if (Auth::user()) {
            $contact->name = Auth::user()->name;
            $contact->email = Auth::user()->email;
        }
        else {
            $contact->name = '';
            $contact->email = '';
        }
        return view('contact', ['contact' => $contact]);
    }


    public function storeContact()
    {
        $user = Auth::user();
        $fields = [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'comment' => ['required', 'string'],
        ];
        if (!$user || $user->cannot('bypass-captcha')) {
            $fields['captcha'] = 'required|captcha';
        }
        $input = $this->validate(request(), $fields);

        $crm = new Crm();
        $crm->name      = $input['name'];
        $crm->email     = $input['email'];
        $crm->comment   = $input['comment'];

        if ($user) {
            $crm->user_id = $user->id;
        }

        $crm->ref = '/contact';

        $crm->save();

        Mail::to('cic-acawriter@uts.edu.au')->send(new ContactMail($crm));
        return redirect()->back()->with('success', 'Thanks for your feedback.');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}
