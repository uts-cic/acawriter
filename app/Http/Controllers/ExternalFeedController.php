<?php

/**
 * Used in demo site - not this codebase
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use EUAutomation\GraphQL\Client;
use App\Services\Analyser;
use App\Feature;
use App\Traits\Profiler;
use App\Traits\Analytical\Cars;
use App\Traits\Analytical\Accounts;
use App\Traits\Analytical\Law;
use App\Traits\Reflective\Pharmacy;
use App\Traits\Reflective\IntlStudies;

class ExternalFeedController extends Controller
{
    use Profiler, Cars, Accounts, Law, Pharmacy, IntlStudies;

    public  $graphQLURL = "";
    public  $client;
    private $metricsWordLength = 25;
    private $para = 3;
    private $rules = array();

    //
    public function __construct()
    {
        // $this->middleware('auth');
        $this->client = new Client($this->graphQLURL);
        // $this->stringTokeniser = new StringTokenizer();
        $this->analyser = new Analyser();
        $this->graphQLURL = env('TAP_API', '') . "/graphql";
    }

    /**
     * Display the specified resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $activityLog = new \stdClass;
        $activityLog->status = 'success';
        $activityLog->data = [];
        //$user_id = Auth::user()->id;
        $result = new \stdClass();

        if ($request['action'] == 'fetch') {
            Log::info('moves', ['execute time : ' => 'started' . date('d/m/y:H:i:s')]);
            $tap = $this->analyser->preProcess($request);
            $result->tap = $tap;

            Log::info('tokeniser', ['tokeniser' => 'completed' . date('d/m/y:H:i:s')]);
        }

        $extra = $request["extra"];
        $result->status = array('message' => 'Success', 'code' => 200);
        $result->rules = array();
        $result->tabs = array();
        if ($extra['feature'] > 0) {
            $feed = $this->getFeedbackSchema('', $extra['feature']);
            $feedbackSchema = json_decode($feed, true);
            $result->rules = $this->rules = $feedbackSchema["rules"];
        } else {
            $path = storage_path() . '/schema/' . $extra['grammar'] . '/' . $extra['feedbackOpt'] . '.json';
            $feedbackSchema = $this->getFeedbackSchema($path, 0);
            $result->rules = $this->rules = $feedbackSchema['rules'];
        }

        //$result->rules= $this->rules = $feedbackSchema['rules'];
        //$result->rules = $feedbackSchema['rules'];
        if (count($this->rules) == 0 || count($tap) == 0) {
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
        if (count($tabbed) > 0) $result->tabs = $tabbed;

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function getFeedbackSchema($path, $id)
    {
        if ($path == '') {
            $features = Feature::find($id);
            $data = $features->rules;
            // print_r($data);
            $json = json_decode($data, true);
        } else {
            $json = json_decode(file_get_contents($path), true);
        }
        return $json;
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
            //$temp->str = nl2br($raw['str']);
            $temp->str = $newLn;
            $temp->css = array();
            $resCss = array();
            // $temp->str = $raw['str'];
            foreach ($this->rules as $rule) {
                $tempcss = array();

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
