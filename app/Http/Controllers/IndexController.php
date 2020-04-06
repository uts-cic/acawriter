<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Document;

class IndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guest()) {
            return view('auth.login');
        }

        $this->authorize('manage-documents');

        $hasDocuments = Document::where('user_id', Auth::id())->count();
        return view('index', ['hasDocuments' => $hasDocuments]);
    }

    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        return view('terms');
    }

    public function help()
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
        return view('help', ['contact' => $contact]);
    }
}
