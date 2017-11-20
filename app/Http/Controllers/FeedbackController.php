<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StoreDrafts;
use EUAutomation\GraphQL\Client;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;
use App\Http\Controllers\StringTokenizer;




class FeedbackController extends Controller
{


    public  $graphQLURL = "http://tap-test.utscic.edu.au/graphql";
    public  $client;
    private $metricsWordLength = 25;
    private $para = 3;
    private $rules = array();


    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->client = new Client($this->graphQLURL);
        $this->stringTokeniser = new StringTokenizer();
    }


    /*
     * Input : $request
     *      tap result - type: array, value :  [str, raw_tags]
     *      action - type: string value: fetch
     *      feddbackOpt - type: string : value :jsonfilename
     */

    public function generateFeedback(Request $request) {
        $tap = $request["tap"];
        $result = new \stdClass();
        $result->status = array('message' => 'Success', 'code' => 200  );
        $path = storage_path().'/schema/'.$request["feedbackOpt"].'.json';
        $result->rules = array();
        $feedbackSchema = $this->getFeedbackSchema($path);
        $result->rules= $this->rules = $feedbackSchema['rules'];


        //$result->rules = $feedbackSchema['rules'];
        if(count($this->rules) == 0 || count($tap) == 0) {
            $result->status['message'] = 'Error';
            $result->status['code'] = 500 ;
            return response()->json($result);
        }

        //create something like // $result->temporality = array();
        foreach($this->rules as $rule) {
            $result->{$rule['name']} = array();
            $methods[] = $rule['name'];
        }


        //go through and call each rule if the rule is defianed
        foreach($this->rules as $rule) {
            $method = $rule['name'];
            if(method_exists($this, $method)) {
               $result->{$method} = $this->{$method}($tap, $rule);
            }
        }
        return response()->json($result);

    }


    protected function getFeedbackSchema($path) {
        if(!$path) {
            return false;
        }

        $json = json_decode(file_get_contents($path),true);

        return $json;
    }



    protected function background($tap, $rule) {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;

        foreach($tap as $key => $data) {
            $setFeed = new \stdClass();
            $setFeed->str = $data['str'];
            $setFeed->message = '';

            if ($key < $check['paragraph'] && count($data['raw_tags']) > 0) {
                if (in_array($check['paragraph'], $data['raw_tags'])) {
                    $tempo++;
                }
            }

            if ($tempo == 0 && $key == $this->para - 1) {
                $setFeed->message = $rule['message'];
                $result[] = $setFeed;
            }
        }
        return $result;


    }

    protected function metrics($tap, $rule) {
        $result = array();
        $check = $rule['check'];

        foreach($tap as $key => $data) {
            $tempStore = new \stdClass();
            $tempStore->str = $data['str'];
            $tempStore->message = '';
            $returnData = $this->stringTokeniser->metrics($data['str']);
            if(isset($returnData->sentWordCounts)) {
                //sentWordCounts is always an array e.g. [5,6] if two sentences sent here we send only one at a time though
                if($returnData->sentWordCounts[0] > $check['sentenceWordCount']) {
                    $tempStore->message = $rule['message'];
                }
            }
            $result[] = $tempStore;
        }
        return $result;

    }


    /*
     * function works on the complete text at once, so input is not tokenised
     * so str = append all tokenised tap outputs into one
     */

    protected function vocab($tap, $rule) {
        $result = array();
        $check = $rule['check'];
        $termCount = 0;
        $words = $check['words'];
        $completeText = "";

        foreach($tap as $key => $data) {
            $completeText .= $data['str'];
        }
        $tempStore = new \stdClass();
        $tempStore->str = $completeText;
        $tempStore->message = '';

            $returnData = $this->stringTokeniser->vocab($tempStore->str);
            if(isset($returnData->terms)) {
                $collection = collect($returnData->terms);

                foreach($check['words'] as $word) {
                    $filtered= $collection->where('term', $word);
                    if(count($filtered->all()) == 0) $termCount++;
                }

                if($termCount > 0 ) {
                    $tempStore->message = $rule['message'];
                    $result[] = $tempStore;
                }
            }

        return $result;
    }

}
