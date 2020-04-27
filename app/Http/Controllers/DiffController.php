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
        $drafts = Draft::where('document_id', $request->document_id)->orderBy('created_at', 'desc')->get(['id', 'document_id', 'text_input', 'user_id', 'created_at', 'feature_id', 'updated_at']);
        $version = count($drafts);
        foreach ($drafts as $draft) {
        	$user = $this->showUsers($draft->user_id);
            $feature = $this->getFeatures($draft->feature_id);
            $document = $this->getDocument($draft->document_id);
        	$draft->user = $user;
            $draft->feature = $feature;
            $draft->version = $version;
            $draft->document = $document;
            $version--;
        	array_push($drafts_users, $draft);
        }
        $data->documents->drafts = $drafts_users;

        return view('admin.report', ['data' => $data]);
    }

    public function showDraftsByDocId(Request $request)
    {
        $data = new \stdClass;
        $data->documents = new \stdClass;
        $data->documents->drafts = array();
        $drafts = array();
        $drafts_users = array();
        $drafts = Draft::where('document_id', $request->id)->orderBy('created_at', 'desc')->get(['id', 'document_id', 'text_input', 'user_id', 'created_at', 'feature_id', 'updated_at']);
        $version = count($drafts);
        foreach ($drafts as $draft) {
            $user = $this->showUsers($draft->user_id);
            $feature = $this->getFeatures($draft->feature_id);
            $document = $this->getDocument($draft->document_id);
            $draft->user = $user;
            $draft->feature = $feature;
            $draft->version = $version;
            $draft->document = $document;
            $version--;
            array_push($drafts_users, $draft);
        }
        $data->documents->drafts = $drafts_users;

        return view('admin.drafts_by_id', ['data' => $data]);
    }

    public function showDocuments(Request $request) 
    {
        $users = User::get(['id', 'name']);
        $user_list = array();
        $feature_list = array();
        $types = array();
        $draftid_featureid_list = array();
        $features = Feature::get(['id', 'name']);
        $draft_feature = Draft::get(['id', 'document_id', 'feature_id']);
        foreach ($features as $key => $value) {
            $feature_list[$value->id] = $value->name;
        }
        foreach ($features as $key => $value) {
            $types[$value->name] = $value->id;
        }
        foreach ($draft_feature as $key => $value) {
            $draftid_featureid_list[$value->document_id] = $feature_list[$value->feature_id];
        }
        foreach ($users as $key => $value) {
            $user_list[$value->id] = $value->name;
        }
        $result_documents = array();
        if ($_GET) {
            $user = User::where('email', $request->email)->first();
            $from_date = $request->from_date. ' ' .$request->from_date_time;
            $to_date = $request->to_date. ' ' .$request->to_date_time;
            if ($user) {
                $documents = Document::where([['user_id', $user->id],['updated_at', '>', $from_date], ['updated_at', '<', $to_date]])->orderBy('updated_at', 'desc')->get(['id', 'user_id', 'name', 'updated_at']);
            } else {
                $documents = Document::where([['updated_at', '>', $from_date], ['updated_at', '<', $to_date]])->orderBy('updated_at', 'desc')->get(['id', 'user_id', 'name', 'updated_at']);
            }
        } else {
            $documents = Document::orderBy('updated_at', 'desc')->get(['id', 'user_id', 'name', 'updated_at']);
        }
        foreach ($documents as $document) {
            $document->user = $user_list[$document->user_id];
            if (array_key_exists($document->id, $draftid_featureid_list)) {
                $document->type = $draftid_featureid_list[$document->id];
            }
            array_push($result_documents, $document);
        }
        if ($_GET['document_type_select']) {
            if ($_GET['document_type_select'] != "None") {
                foreach ($result_documents as $key=>$document) {
                    if ($document->type != $_GET['document_type_select']) {
                        unset($result_documents[$key]);
                    }
                }
            }
        }
        return view('admin.result_documents', ['documents' => $result_documents, 'types' => $types]);
    }

    public function showUsers($id)
    {
    	$data = new \stdClass;
        $users = User::where('id', $id)->first('name');
        $data->users = $users;
        return $data;
    }

    public function getVersions($id)
    {
        $data = new \stdClass;
        $drafts = Draft::where('document_id', $id)->orderBy('created_at', 'desc')->get(['id', 'document_id', 'created_at', 'updated_at']);
        $version = count($drafts);
        foreach ($drafts as $draft) {
            $draft->version = $version;
            $version--;
        }

        return $drafts;
    }

    public function getDocument($id)
    {
        $document = Document::where('id', $id)->first(['id', 'name']);
        return $document;
    }

    public function getDocuments(Request $request)
    {
        $user_documents = new \stdClass;
        $user = User::where('name', $request->username)->first(['id', 'name']);
        if ($user) {
            $user_documents = Document::where('user_id', $user->id)->get(['id', 'user_id', 'name']);
            $user_documents->user = $user;
        }
        return view('admin.report', ['user_documents' => $user_documents]);
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
        if ($request->id) {
            $draft_first = Draft::where('id', '=', $request->id)->get(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id']);
        } else {
            $draft_first = Draft::where('id', '=', '1')->get(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id']);
        }
        if ($request->id_to) {
            $draft_second = Draft::where([['document_id', '=', $draft_first[0]->document_id], ['id', '=', $request->id_to]])->orderBy('created_at', 'desc')->first(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id', 'updated_at']);
        } else {
            $draft_second = Draft::where([['id', '!=', $request->id], ['document_id', '=', $draft_first[0]->document_id]])->orderBy('created_at', 'desc')->first(['id', 'document_id', 'raw_response', 'text_input', 'user_id', 'created_at', 'feature_id', 'updated_at']);
        }
    	$data = new \stdClass;
    	$data->draft_first = $draft_first[0];
        $data->draft_second = $draft_second;
        $data->draft_first->features = $this->getFeatures($data->draft_first->feature_id);
        $data->draft_second->features = $this->getFeatures($data->draft_second->feature_id);
        $data->draft_first->raw_response = json_decode($draft_first[0]->raw_response);
    	$data->draft_second->raw_response = json_decode($draft_second->raw_response);

        $draft_version = new \stdClass;
        $draft_version = $this->getVersions($data->draft_first->document_id);

    	return view('admin.diffreport', ['data' => $data, 'versions' => $draft_version]);
    }
}
?>