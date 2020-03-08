<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Assignment;
use App\Document;
use App\Draft;
use App\User;
use App\Role;
use App\Events\UserRegistered;

class DiffController extends Controller 
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function showDrafts(Request $request)
    {
        $data = new \stdClass;
        $data->documents = array();
        $drafts = array();
        $drafts_users = array();
        $drafts = Draft::where('document_id', $request->document_id)->orderBy('created_at', 'desc')->get(['document_id', 'text_input', 'user_id']);
        foreach ($drafts as $draft) {
        	$user = $this->showUsers($draft->user_id);
        	$draft->user = $user;
        	array_push($drafts_users, $draft);
        }
        $data->documents = $drafts_users;
        return view('admin.report', ['data' => $data]);
        // return response()->json($data);
    }

    public function showUsers($id)
    {
    	$data = new \stdClass;
        $users = User::where('id', $id)->first('name');
        $data->users = $users;
        // return view('admin.report', ['data' => $data]);
        // return response()->json($data);
        return $data;
    }
}
?>