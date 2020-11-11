<?php

/**
 * Project: AcaWriter
 *
 * Copyright(c)2018 original University of Technology Sydney.
 * Licensed under the Apache License, Version2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * See the License for the specific language governing permissions and limitations under the License.
 *
 *  Contributor(s):
 *  UTS Connected Intelligence Centre
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\StoreDraftWithoutFeedback;
use EUAutomation\GraphQL\Client;
use Auth;
use App\User;
use App\Services\Analyser;
use App\Feature;
use App\Draft;
use App\Events\UserActivity;
use App\Traits\Profiler;
use App\Traits\Analytical\Cars;
use App\Traits\Analytical\Accounts;
use App\Traits\Analytical\Law;
use App\Traits\Reflective\Pharmacy;
use App\Traits\Reflective\IntlStudies;
use App\Traits\Analytical\CarsAbs;


class FeedbackController extends Controller
{
    use Profiler, Cars, Accounts, Law, Pharmacy, IntlStudies, CarsAbs;

    protected $analyser;
    protected $rules;

    public function __construct()
    {
        $this->middleware('auth');
        $this->analyser = new Analyser();
        $this->rules = array();
    }

    /*
     * Input : $request
     *      tap result - type: array, value :  [str, raw_tags]
     *      action - type: string value: fetch
     *      feddbackOpt - type: string : value :jsonfilename
     */
    public function generateFeedback(Request $request)
    {
        $result = new \stdClass();

        $tap = array();
        if ($request['action'] === 'quick') {
            // single sentence change analysis
            // we need to send a request to get the tap raw tags
            // input is always going to be string as output by tokeniser
            $data = array();
            $data['txt'] = $request['txt'];
            $data['grammar'] = $request['extra']['grammar'];
            $temp = $this->analyser->quickTapMoves($data);
            $tap[] = $temp;
        }
        elseif ($request['action'] === 'fetch') {
            Log::info('moves', ['execute time : ' => 'started' . date('d/m/y:H:i:s')]);

            $tap = $this->analyser->preProcess($request);
            $result->tap = $tap;

            Log::info('tokeniser', ['tokeniser' => 'completed' . date('d/m/y:H:i:s')]);
        }

        $extra = $request['extra'];
        $result->status = array('message' => 'Success', 'code' => 200);
        $result->rules = array();
        $result->tabs = array();
        $jobRef = $extra['storeDraftJobRef'];
        if ($extra['feature']) {
            $feed = $this->getFeedbackSchema('', $extra['feature']);
            $feedbackSchema = json_decode($feed, true);
            $result->rules = $this->rules = $feedbackSchema['rules'];
        }
        else {
            $path = storage_path() . '/schema/' . $extra['grammar'] . '/' . $extra['feedbackOpt'] . '.json';
            $feedbackSchema = $this->getFeedbackSchema($path, 0);
            $result->rules = $this->rules = $feedbackSchema['rules'];
        }

        if (empty($this->rules) || empty($tap)) {
            $result->status['message'] = 'Error';
            $result->status['code'] = 500;
            return response()->json($result);
        }

        //create something like // $result->temporality = array();
        foreach ($this->rules as $rule) {
            $result->{$rule['name']} = array();
            $methods[] = $rule['name'];
        }

        //go through and call each rule if the rule is defined
        foreach ($this->rules as $rule) {
            $method = isset($rule["method"]) ? $rule["method"] : $rule['name'];
            $name = $rule['name'];

            $tab = isset($rule['tab']) ? $rule['tab'] : 1;

            if (method_exists($this, $method) && $tab == 1) {
                $result->{$name} = $this->{$method}($tap, $rule);
            }
        }

        $final = $this->formatFeedback($tap, $result);
        $result->final = $final;

        //evaluate for all other tabs
        $tabbed = array();
        foreach ($this->rules as $rule) {
            $method = isset($rule["method"]) ? $rule["method"] : $rule['name'];
            $name = $rule['name'];
            $tab = isset($rule['tab']) ? $rule['tab'] : 1;
            $subTab = new \stdClass;

            if (method_exists($this, $method) && $tab > 1) {
                $more = array();
                if (isset($result->expression)) {
                    if (count($result->expression) > 0) {
                        array_push($more, array('expression' => $result->expression));
                    }
                }
                $subTab->{$name} = $this->{$method}($tap, $rule, $more);
                if (!isset($tabbed[$tab])) $tabbed[$tab] = array();
                array_push($tabbed[$tab], $subTab);
            }
        }

        //now just append other tabs to the result
        if (count($tabbed) > 0) {
            $result->tabs = $tabbed;
        }

        /*
         * this is an extension applied to fetch feedback and save all at one go!
         * provided init Feedback is set to true
         *
         */
        if ($request['extra']['initFeedback']) {
            $user = Auth::user();

            $draft = new Draft();
            $draft->text_input = $request['txt'];
            $draft->feature_id = $request['extra']['feature'];
            $draft->document_id = $request['document'];
            $draft->raw_response = json_encode($result);
            $draft->user_id = $user->id;
            $draft->is_auto = $request['type'] == 'manual' ? 0 : 1;
            $draft->save();

            $activityLog = new \stdClass;
            $activityLog->status = $draft->id ? 'success' : 'error';
            $activityLog->data = [];
            $activityLog->msg = "Draft saved";
            $activityLog->user = $user;
            $activityLog->type = 'Draft';
            $activityLog->ref = $draft;
            $activityLog->jobRef = $jobRef;

            event(new UserActivity($user, $activityLog));
            Log::info('Draft stored after fetch feedback', ['draft' => $draft]);
        }

        return response()->json($result);
    }

    /* stores draft by generating a job **/
    public function storeFeedback(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        StoreDraftWithoutFeedback::dispatchNow($data, $user);
    }

    protected function getFeedbackSchema($path, $id)
    {
        if ($path) {
            return json_decode(file_get_contents($path), true);
        }

        $features = Feature::find($id);
        $data = $features->rules;
        return json_decode($data, true);
    }

    /**
     *  applicable only for reflective feedback
     */
    protected function formatFeedback($tap, $result)
    {
        $final = array();

        foreach ($tap as $key => $raw) {
            $temp = new \stdClass();
            $newLn = str_replace("[&][&]", "<br />", $raw->str);
            $temp->str = $newLn;
            $temp->css = array();

            $resCss = array();
            foreach ($this->rules as $rule) {
                if (isset($result->{$rule['name']}[$key])) {
                    $temp->{$rule['name']} = $result->{$rule['name']}[$key];
                    $resCss = array_merge($resCss, $result->{$rule['name']}[$key]->css);
                }
            }
            $temp->css = $resCss;
            $final[] = $temp;
        }

        Log::info('feedback', ['feed' => 'completed' . date('d/m/y:H:i:s')]);
        return $final;
    }
}
