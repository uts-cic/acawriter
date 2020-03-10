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
use App\Example;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-documents']);
    }

    /**
     * Get all documents for the user
     */
    public function index()
    {
        $list = new \stdClass;
        $list->documents = array();
        $assignments = array();

        $user_id = Auth::user()->id;

        $documentList = DB::table('user_subscription')
            ->select('assignment_id')
            ->where('user_id', '=', $user_id)
            ->get();

        $assignmentIds = array();
        if (count($documentList) > 0) {
            foreach ($documentList as $a) {
                $assignmentIds[] =  $a->assignment_id;
            }

            $assignments = Assignment::withTrashed()->whereIn('id', $assignmentIds)->with('feature')->get();
            $list->documents = Document::whereIn('assignment_id', $assignmentIds)
                ->where('user_id', $user_id)
                ->with('assignment')->get();
        }

        if (count($list->documents) > 0 && count($assignments) > 0) {
            foreach ($list->documents as $document) {
                $draft = Draft::where('document_id', $document->id)->orderBy('created_at', 'desc')->first();
                $document->draft_last_updated_at = '';
                foreach ($assignments as $assignment) {
                    if ($document->assignment_id === $assignment->id) {
                        if ($draft) {
                            if (strtotime($draft->created_at) > strtotime($document->created_at)) {
                                $document->draft_last_updated_at = strtotime($draft->created_at);
                            } else {
                                $document->draft_last_updated_at = strtotime($document->created_at);
                            }
                        } else {
                            $document->draft_last_updated_at = strtotime($document->created_at);
                        }
                        $document->feature_id = $assignment->feature->id;
                        $document->grammar = $assignment->feature->grammar;
                        $document->feature_name = $assignment->feature->name;
                    }
                }
            }
            $tmp = new Collection($list->documents);

            $sorted = $tmp->sortByDesc('draft_last_updated_at');
            $list->documents = $sorted->values()->all();
        }

        return response()->json($list);
    }


    public function create(Request $request)
    {
        $this->validate(request(), [
            'doc_name' => 'required',
            'doc_type' => 'required'
        ]);

        $name = trim(htmlspecialchars($request->doc_name, ENT_QUOTES, 'UTF-8'));
        $feature_id = intval($request->doc_type);

        $assignment = $this->createAssignment('NA', $feature_id);
        $subscribed = $assignment && $this->createSubscription($assignment);
        $document = $subscribed ? $this->createDocument($assignment, $name) : null;

        if (!$document) {
            return redirect()->back()->with('error', 'Sorry, the document could not be created. Please try again.');
        }

        return redirect('/analyse/' . $document->slug);
    }

    public function subscribe(Request $request)
    {
        $this->validate(request(), [
            'assignment_code' => 'required'
        ]);

        $code = trim(htmlspecialchars($request['assignment_code'], ENT_QUOTES, 'UTF-8'));

        $user_id = Auth::user()->id;

        $assignment = Assignment::where('code', 'ILIKE', $code)->first();
        if (!$assignment) {
            return redirect()->back()->with('error', 'Sorry, assignment code <strong>' . $code . '</strong> could not be found.');
        }

        $subscribed = $this->createSubscription($assignment);
        if (!$subscribed) {
            return redirect()->back()->with('error', 'Sorry, could not subscribe to assignment code <strong>' . $code . '</strong>. Please try again.');
        }

        $document = $this->createDocument($assignment);
        if (!$document) {
            return redirect()->back()->with('error', 'Sorry, the document could not be created. Please try again.');
        }

        if (!empty($assignment->example_id)) {
            $example = Example::find($assignment->example_id);
            $this->createDraftFromExample($example, $document, $assignment);
        }

        return redirect('/analyse/' . $document->slug);
    }

    public function update(Request $request)
    {
        $success = false;
        $id = isset($request->id) ? $request->id : null;
        $name = isset($request->name) ? $request->name : null;

        if (!empty($id) && !empty($name)) {
            $user_id = Auth::user()->id;
            $document = Document::where('user_id', $user_id)->find($id);
            $document->name = $name;
            $success = $document->save();
        }
        if ($success) {
            $response = ['success' => true, 'message' => 'Document name has been updated.'];
        }
        else {
            $response = ['success' => false, 'message' => 'Document name could not be updated.'];
        }
        return response()->json($response, 200);
    }

    public function delete(Request $request)
    {
        $success = false;
        if (!empty($request->id)) {
            $user_id = Auth::user()->id;
            Draft::where('document_id', $request->id)->where('user_id', $user_id)->delete();
            $success = Document::where('id', $request->id)->where('user_id', $user_id)->delete();
        }
        if ($success) {
            $response = ['success' => true, 'message' => 'Document has been deleted.'];
        }
        else {
            $response = ['success' => false, 'message' => 'Document could not be deleted.'];
        }
        return response()->json($response, 200);
    }

    private function createAssignment($name, $feature_id)
    {
        $assignment = new Assignment();
        $assignment->name = $name;
        $assignment->feature_id = $feature_id;
        $assignment->code = Str::random(8);
        $assignment->user_id = Auth::user()->id;
        $assignment->keywords = '';
        $assignment->published = 0;
        $assignment->updated_at = date('Y-m-d H:i:s');
        $assignment->created_at = date('Y-m-d H:i:s');
        $assignment->save();

        return $assignment->id ? $assignment : null;
    }

    private function createDocument($assignment, $name = null)
    {
        $document = new Document();
        $document->name = $name ? $name : $assignment->name;
        $document->user_id = Auth::user()->id;
        $document->slug = strtolower(Str::random(22));
        $document->assignment_id = $assignment->id;
        $document->created_at = date('Y-m-d H:i:s');
        $document->updated_at = date('Y-m-d H:i:s');
        $document->save();

        return $document->id ? $document : null;
    }

    /**
     * Create draft from example data
     * @param $example
     * @param $document
     * @param $assignment
     *
     * @return Draft
     */
    private function createDraftFromExample($example, $document, $assignment)
    {
        $draft = new Draft();
        $draft->text_input = $example->text_input;
        $draft->feature_id = $assignment->feature_id;
        $draft->document_id = $document->id;
        $draft->raw_response = $example->raw_response; //already json_decoded
        $draft->user_id = Auth::user()->id;
        $draft->is_auto = 0;
        $draft->created_at = date('Y-m-d H:i:s');
        $draft->updated_at = date('Y-m-d H:i:s');
        $draft->save();

        return $draft->id ? $draft : null;
    }

    private function createSubscription($assignment)
    {
        $user_id = Auth::user()->id;

        //skip insert if already subscribed
        $subscribed = DB::table('user_subscription')->where([
            ['user_id', '=', $user_id],
            ['assignment_id', '=', $assignment->id]
        ])->count();

        if (!$subscribed) {
            $subscribed = DB::table('user_subscription')->insert([
                [
                    'user_id' => $user_id,
                    'assignment_id' => $assignment->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]);
        }

        return $subscribed;
    }
}
