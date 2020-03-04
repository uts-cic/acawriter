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

    public function showDrafts($query)
    {
        $data = new \stdClass;
        $data->documents = array();
        $drafts = array();
        $drafts_users = array();
        $drafts = Draft::where('document_id', $query->id)->orderBy('created_at', 'desc')->get();
        foreach ($drafts as $draft) {
        	$user = showUsers($draft->user_id);
        	$draft->user = $user;
        	array_push($drafts_users, $draft);
        }
        $data->documents = $drafts_users;

        return response()->json($data);
    }

    public function showUsers($id)
    {
    	$data = new \stdClass;
        $users = User::where('user_id', id);
        $data->users = $users;
        return response()->json($data);
    }
}
?>