<?php

namespace App\Http\Controllers;

use Auth;
use App\Document;
use App\Draft;
use App\Feature;
use App\TextDraft;

class AnalyseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-documents']);
    }

    public function index($code = NULL)
    {
        $user_id = Auth::user()->id;
        $document = Document::where('slug', '=', $code)
            ->where('user_id', $user_id)
            ->with('assignment')
            ->first();

        if (!$document) {
            return redirect('/');
        }

        $document->feature = Feature::where('id', $document->assignment->feature_id)->get();

        $draft = Draft::where('document_id', $document->id)->orderBy('created_at', 'desc')->first();
        $textDraft = TextDraft::where('document_id', $document->id)->orderBy('created_at', 'desc')->first();

        if ($textDraft && $draft) {
            if (strtotime($textDraft->created_at) > strtotime($draft->created_at)) {
                $document->textDraft = $textDraft;
            } else {
                $document->draft = $draft;
            }
        } else if ($textDraft) {
            $document->textDraft = $textDraft;
        } else {
            $document->draft = $draft;
        }

        return view('analyse', ['document' => $document]);
    }
}
