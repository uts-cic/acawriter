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

        if($request['action'] == 'quick') {
            //we need to send a request to get the tap raw tags
            // input is always going to be string as output by tokeniser
            $tap = $tt = array();

            $data = array();
            $data['txt'] = $request['txt'];
            $data['grammar'] = $request['extra']['grammar'];
            $temp = $this->stringTokeniser->quickTapMoves($data);
            $tt['str']= $temp->str ? $temp->str : '';
            $tt['raw_tags'] = $temp->raw_tags? $temp->raw_tags : array();
            $tt['tags'] = $temp->tags? $temp->tags:'';
            $tap[]=$tt;

        } else if($request['action'] == 'fetch') {
            $tap = $request["tap"];

        }


        $result = new \stdClass();
        $extra = $request["extra"];
        $result->status = array('message' => 'Success', 'code' => 200  );
        $path = storage_path().'/schema/'.$extra['grammar'].'/'.$extra['feedbackOpt'].'.json';
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


        //go through and call each rule if the rule is defined
        foreach($this->rules as $rule) {
            $method = $rule['name'];
            if(method_exists($this, $method)) {
               $result->{$method} = $this->{$method}($tap, $rule);
            }
        }

        $final = $this->formatFeedback($tap, $result);
        $result->final = $final;

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
            $setFeed->message = array();
            $setFeed->css = array();
            if ($key < $check['paragraph'] && count($data['raw_tags']) > 0) {
                if (in_array($check['paragraph'], $data['raw_tags'])) {
                    $tempo++;
                }
            }
        }

        if ($tempo == 0 && $key == $this->para - 1) {
            $setFeed->message['background'] = $rule['message'];
            $setFeed->css[] = 'background';
            $result[] = $setFeed;
        }

        return $result;
    }

    protected function metrics($tap, $rule) {
        $result = array();
        $check = $rule['check'];

        foreach($tap as $key => $data) {
            $tempStore = new \stdClass();
            $tempStore->str = $data['str'];
            $tempStore->message = array();
            $tempStore->css = array();
            $returnData = $this->stringTokeniser->metrics($data['str']);
            if(isset($returnData->sentWordCounts)) {
                //sentWordCounts is always an array e.g. [5,6] if two sentences sent here we send only one at a time though
                if($returnData->sentWordCounts[0] > $check['sentenceWordCount']) {
                    //$tempStore->message = $rule['message'];
                    foreach($rule['message'] as $msg) {
                        if(isset($msg['metrics'])) {
                            $tempStore->message['metrics'] = $msg['metrics'];
                            array_push($tempStore->css, 'metrics');
                        }
                    }
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
        $tempStore->message = array();
        $tempStore->css =array();

            $returnData = $this->stringTokeniser->vocab($tempStore->str);
            if(isset($returnData->terms)) {
                $collection = collect($returnData->terms);

                foreach($check['words'] as $word) {
                    $filtered= $collection->where('term', $word);
                    if(count($filtered->all()) == 0) $termCount++;
                }

                if($termCount > 0 ) {
                    foreach($rule['message'] as $msg) {
                        if(isset($msg['metrics'])) {
                            $tempStore->message['vocab'] = $msg['vocab'];
                            $tempStore->css[] = 'vocab';
                        }
                    }
                    $result[] = $tempStore;
                }
            }

        return $result;
    }

    /*
     * *** applicable only for reflective feedback
     */

    protected function expression($tap, $rule) {
        $result = array();
        $check = $rule['check'];
        $termCount = 0;
        $all = $check['all'];


        if(count($all) == 0) {
            return $result;
        }


        foreach($tap as $key => $data) {
            $tempStore = new \stdClass();
            $tempStore->str = $data['str'];
            $tempStore->message = array();
            $tempStore->affect=array();
            $tempStore->epistemic=array();
            $tempStore->modal=array();
            $tempStore->css = array();

            $returnData = $this->stringTokeniser->expression($data['str']);
            //$returnData is an array but since we are analysing tokenised strings we can safetly assume array[0]
            $sanitizedResult = $returnData[0];
            //$tempStore->raw = $sanitizedResult;
            foreach($all as $exp) {

                if (isset($sanitizedResult->{$exp}) && count($sanitizedResult->{$exp}) > 0) {
                    $tempStore->{$exp} = $sanitizedResult->{$exp};
                    foreach($rule['message'] as $msg) {
                        if(isset($msg[$exp])) {
                            $tempStore->message[$exp] = $msg[$exp];
                            array_push($tempStore->css, $exp);
                        }
                    }
                }
            }

            $result[] = $tempStore;
        }


        return $result;

    }


    protected function moves($tap, $rule) {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        if(count($tags) == 0) {
            return $result;
        }

        foreach($tap as $key => $data) {
            $setFeed = new \stdClass();
            $setFeed->str = $data['str'];
            $setFeed->message = array();
            $setFeed->css = array();

            foreach($tags as $tag) {
                if(count(preg_grep("[^".$tag."]", $data['raw_tags'])) > 0) {
                    foreach($messages as $msg) {
                        if(isset($msg[$tag])) {
                            $setFeed->message[$tag] = $msg[$tag];
                            array_push($setFeed->css,$tag);
                        }
                    }
                }
            }
            $result[] = $setFeed;
        }

        return $result;
    }

    protected function formatFeedback($tap, $result) {
        $final = array();

        foreach($tap as $key => $raw) {
            $temp = new \stdClass();
            $newLn=str_replace("[&][&]", "<br />", $raw['str']);
            //$temp->str = nl2br($raw['str']);
            $temp->str = $newLn;
            $temp->css = array();
            $resCss= array();
           // $temp->str = $raw['str'];
            foreach($this->rules as $rule) {
                $tempcss= array();

                if(isset($result->{$rule['name']}[$key])) {
                    $temp->{$rule['name']} = $result->{$rule['name']}[$key];
                    $resCss= array_merge($resCss, $result->{$rule['name']}[$key]->css);
                }
            }
            $temp->css = $resCss;
            $final[]=$temp;
        }
        return $final;




    }



}
