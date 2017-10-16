<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EUAutomation\GraphQL\Client;
use Illuminate\Support\Facades\Hash;


class StringTokenizer extends Controller
{

    public $gResponse;
    public $graphQLURL = "http://tap-test.utscic.edu.au/graphql";
    public $client;

    protected $query = "query 
                    CleanText(\$input: String!) {
                        clean(text:\$input) {
                                analytics
                                timestamp
                        }
                        cleanPreserve(text:\$input) {
                            analytics
                        }
                        cleanMinimal(text:\$input) {
                                analytics
                        }
                        cleanAscii(text:\$input) {
                                analytics
                        }
                }";

    protected $queryOne = "query Athanor(\$input: String!) {
                          moves(text:\$input) {
                            analytics
                          }
                    }";

    protected $qm       = "query Metrics(\$input: String!) {
                            metrics(text:\$input) {
                                analytics {
                                    wordCount
                    }
                    timestamp
                  }
                }";

    protected $queryTwo = "query Vocab(\$input: String!) {
                        vocabulary(text:\$input){
                                analytics {
                                    unique
                                        terms {
                                            term
                                            count
                                        }
                                }
                                timestamp
                            }
                        }";

    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->client = new Client($this->graphQLURL);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');

    }


    public function process(Request $request)
    {

        $results = new \stdClass();

        if ($request["action"] == 'athanor') {
            $results->athanor = $this->analyseAthanor($request);
        }

        if ($request["action"] == 'vocab') {
            $results->vocab = $this->analyseVocab($request);
        }

        if ($request["action"] == 'qathanor') {
            $results->athanor = $this->qanalyseAthanor($request);
        }


        return response()->json($results);

    }


    //full text analysis
    protected function analyseAthanor(Request $request) {
        $apiResponse = new \StdClass();
        $collection = collect($request['txt']);
        $unique = $collection->unique();
        $unique->values()->all();
        $queryTxt = strip_tags($unique->last());
        $variables = new \stdClass();
        $variables->input = $queryTxt;
        $split = preg_split('/[.]/', $queryTxt);

        $originalHash = Hash::make($queryTxt);
        $responseHash = '';

        if ($originalHash != $responseHash) {
            //get athanor
            $this->gResponse = $this->client->response($this->queryOne, $variables);
            if ($this->gResponse->hasErrors()) {
                dd($this->gResponse->errors());
            } else {
                $res = $this->gResponse->moves->analytics;
                $apiResponse = $this->aggregateData($split, $res);
            }

        }
        return $apiResponse;
    }

    protected function analyseVocab(Request $request) {
        $apiResponse = new \StdClass();
        $collection = collect($request['txt']);
        $unique = $collection->unique();
        $unique->values()->all();
        $queryTxt = strip_tags($unique->last());
        $variables = new \stdClass();
        $variables->input = $queryTxt;
        $split = preg_split('/[.]/', $queryTxt);

        $originalHash = Hash::make($queryTxt);
        $responseHash = '';

        if ($originalHash != $responseHash) {
            //get vocabulary
            $this->gResponse = $this->client->response($this->queryTwo, $variables);
            if($this->gResponse->hasErrors()) {
                dd($this->gResponse->errors());
            } else {
                $apiResponse = $this->gResponse->vocabulary->analytics;
            }
        }
        return $apiResponse;

    }


    //quick sentence by sentence
    protected function qanalyseAthanor(Request $request) {
        $apiResponse = new \StdClass();
        $queryTxt = strip_tags($request['txt']);
        $variables = new \stdClass();
        $variables->input = $queryTxt;

            //get athanor
            $this->gResponse = $this->client->response($this->queryOne, $variables);
            if ($this->gResponse->hasErrors()) {
                dd($this->gResponse->errors());
            } else {
                $res = $this->gResponse->moves->analytics;
                foreach($res as $rest) {
                    $apiResponse->str = $queryTxt;
                    $apiResponse->tags = implode(", ", $rest);
                }
            }
        return $apiResponse;
    }



    protected function aggregateData(array $original, array $res) {
        $result = new \stdClass();
        $result->responseTxt = [];
        $result->status = false;


        if(!is_array($original) || !is_array($res)) {
             return $result;
        } elseif(count($original) == 0 || count($res)== 0) {
            return $result;
        } else {
            foreach ($original as $key => $txt) {
                $tempTxt = new \stdClass();
                if ($txt!="") {
                    $tempTxt->str = $txt;
                    $tempTxt->tags= "";
                    if(isset($res[$key]) ){
                        $tempTxt->tags = $res[$key]!= "" ? ( implode(", ", $res[$key])) : "";
                    }
                    $result->responseTxt[] = $tempTxt;
                    $result->status = true;
                }
            }
            return $result;
        }
    }




}
