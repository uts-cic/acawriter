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
use App\Feature;
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
        $data->documents = new \stdClass;
        $data->documents->drafts = array();
        $drafts = array();
        $drafts_users = array();
        $drafts = Draft::where('document_id', $request->document_id)->orderBy('created_at', 'desc')->get(['id', 'document_id', 'text_input', 'user_id', 'created_at', 'feature_id']);
        $version = 1;
        foreach ($drafts as $draft) {
        	$user = $this->showUsers($draft->user_id);
            $feature = $this->getFeatures($draft->feature_id);
        	$draft->user = $user;
            $draft->feature = $feature;
            $draft->version = $version;
            $version++;
        	array_push($drafts_users, $draft);
        }
        $data->documents->drafts = $drafts_users;
        return view('admin.report', ['data' => $data]);
    }

    public function showUsers($id)
    {
    	$data = new \stdClass;
        $users = User::where('id', $id)->first('name');
        $data->users = $users;
        return $data;
    }

    public function getFeatures($id)
    {
        $data = new \stdClass;
        $features = Feature::where('id', $id)->first(['name', 'grammar', 'rules']);
        return $features;
    }

    public function produceReport(Request $request)
    {
    	$draft_first = new \stdClass;
    	$draft_second = new \stdClass;
        $version = $request->version;
    	$draft_first = Draft::where('id', '=', $request->id)->get(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id']);
        if ($version) {
            $draft_second = Draft::where([['document_id', '=', $draft_first[0]->document_id], ['created_at', '=', $version]])->orderBy('created_at', 'desc')->first(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id']);
        } else {
            $draft_second = Draft::where([['id', '!=', $request->id], ['document_id', '=', $draft_first[0]->document_id]])->orderBy('created_at', 'desc')->first(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id']);
        }
    	$data = new \stdClass;
    	$data->draft_first = $draft_first[0];
        $data->draft_second = $draft_second;
        $data->draft_first->features = $this->getFeatures($data->draft_first->feature_id);
        $data->draft_second->features = $this->getFeatures($data->draft_second->feature_id);
        $data->draft_first->raw_response = json_decode($draft_first[0]->raw_response);
    	$data->draft_second->raw_response = json_decode($draft_second->raw_response);

    	return view('admin.diffreport', ['data' => $data]);
    }
}
?>