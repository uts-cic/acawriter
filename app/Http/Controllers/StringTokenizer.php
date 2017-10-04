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


    public function process(Request $request) {

        $collection = collect($request['txt']);
        $unique = $collection->unique();
        $unique->values()->all();
        $queryTxt = strip_tags($unique->last());
        $variables = new \stdClass();
        $variables->input =  $queryTxt;
        $split  = preg_split('/[.]/',$queryTxt);

        $query = "query 
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

        $queryOne = "query Athanor(\$input: String!) {
                          moves(text:\$input) {
                            analytics
                          }
                    }";

        $qm       = "query Metrics(\$input: String!) {
                            metrics(text:\$input) {
                                analytics {
                                    wordCount
                    }
                    timestamp
                  }
                }";



                $originalHash = Hash::make($queryTxt);
                $responseHash = '';

                if($originalHash != $responseHash) {
                    $this->gResponse = $this->client->response($queryOne, $variables);
                    if($this->gResponse->hasErrors()) {
                        dd($this->gResponse->errors());
                    } else {
                        $res = $this->gResponse->moves->analytics;

                        $value = $this->aggregateData($split, $res);
                        return response()->json($value);
                    }

                }

    }


    public function aggregateData(array $original, array $res) {
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
                        $tempTxt->tags = $res[$key]!= "" ? ( implode(",", $res[$key])) : "";
                    }
                    $result->responseTxt[] = $tempTxt;
                    $result->status = true;
                }
            }
            return $result;
        }
    }




}
