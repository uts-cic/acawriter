<?php

/**
 * Project: AcaWriter
 * Copyright (c) 2018 original University of Technology Sydney. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributor(s):
 * Developer UTS Connected Intelligence Centre
 *
 */

namespace App\Services;

use EUAutomation\GraphQL\Client;
use Html2Text\Html2Text;

class Analyser
{
    public $gResponse;
    public $graphQLURL = "";
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
    protected $queryOneR = "query RhetoricalMoves(\$input: String!, \$parameters:String) {
                          moves(text:\$input,parameters:\$parameters) {
                            analytics
                            message
                            timestamp
                            querytime
                          }
                        }";
    protected $queryMoves = "query RhetoricalMoves(\$input: String, \$parameters:String) {
                          moves(text:\$input,parameters:\$parameters) {
                            analytics
                            message
                            timestamp
                            querytime
                          }
                        }";
    protected $queryOneA = "query RhetoricalMoves(\$input: String!) {
                          moves(text:\$input,parameters:{\"grammar\": \"analytic\"}) {
                            analytics
                            message
                            timestamp
                            querytime
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
    protected $queryTokenise = "query Tokenise(\$input: String!) {
                                    annotations(text:\$input) {
                                        analytics {
                                            original
                                            idx
                                            start
                                            end
                                            length
                                            tokens {
                                                idx
                                                term
                                                lemma
                                                postag
                                            }
                                        }
                                    }
                                }";
    protected $queryMetrics = "query Metrics(\$input: String!) {
                                  metrics(text:\$input) {
                                      analytics {
                                            sentences
                                            tokens
                                            words
                                            characters
                                            punctuation
                                            whitespace
                                            sentWordCounts
                                            averageSentWordCount
                                            wordLengths
                                            averageWordLength
                                            averageSentWordLength
                                      }
                                      timestamp
                                  }
                                }";
    protected $queryExpressions = "query Expressions(\$input: String!) {
                                    expressions(text:\$input) {
                                        analytics {
                                            sentIdx
                                            epistemic {
                                                text
                                                startIdx
                                                endIdx
                                            }
                                            modal {
                                                text
                                            }
                                        }
                                    }
                                }";
    // AI/2019-06-25: Removing affect analysis
    // protected $queryAffectExpression = "query Affect(\$input: String, \$parameters:String) {
    //                                 affectExpressions(text:\$input,parameters:\$parameters) {
    //                                     message
    //                                     timestamp
    //                                     querytime
    //                                     analytics {
    //                                         affect {
    //                                             text
    //                                             valence
    //                                             arousal
    //                                             dominance
    //                                             startIdx
    //                                             endIdx
    //                                         }
    //                                     }
    //                                 }
    //                             }";

    public function __construct()
    {
        //$this->middleware('auth');
        $this->graphQLURL = env('TAP_API', '') . "/graphql";
        $this->client = new Client($this->graphQLURL);
    }


    /**
     *   TAP: query to retrive sentence level metrics
     *   input: string single sentence
     */
    public function metrics($string)
    {
        $apiResponse = new \StdClass();
        $variables = new \stdClass();
        //$variables->input = strip_tags($string);
        $variables->input = $this->cleanText($string);
        $apiResponse = new \stdClass();
        //get  metrics
        $this->gResponse = $this->client->response($this->queryMetrics, $variables);
        if ($this->gResponse->hasErrors()) {
            $apiResponse = $this->gResponse->errors();
        } else {
            $apiResponse = $this->gResponse->metrics->analytics;
        }
        return $apiResponse;
    }

    /**
     *  TAP : query
     *  input: string single sentence
     */
    public function vocab($string)
    {
        $apiResponse = new \StdClass();
        $variables = new \stdClass();
        //$variables->input = strip_tags($string);
        $variables->input = $this->cleanText($string);
        //get  metrics
        $this->gResponse = $this->client->response($this->queryTwo, $variables);
        if ($this->gResponse->hasErrors()) {
            $apiResponse = $this->gResponse->errors();
        } else {
            $apiResponse = $this->gResponse->vocabulary->analytics;
        }
        return $apiResponse;
    }

    /**
     *  Athanor: query Used retrive expressions
     *  input: string single sentence
     *  normally only used for reflective feedback
     *  output is an array
     */
    // AI/2019-06-25: Removing affect analysis
    // public function expression($string, $affectVal = '')
    public function expression($string)
    {
        $apiResponse = new \StdClass();
        $variables = new \stdClass();
        //$variables->input = $this->cleanText($string);
        $variables->input = $this->cleanText($string);
        //get  metrics
        $this->gResponse = $this->client->response($this->queryExpressions, $variables);
        if ($this->gResponse->hasErrors()) {
            $apiResponse = $this->gResponse->errors();
        } else {
            $apiResponse = $this->gResponse->expressions->analytics;
        }

        // AI/2019-06-25: Removing affect analysis
        //affectExpr
        // if ($affectVal != '') {
        //     $variables->parameters = json_encode($affectVal);
        // }

        // $this->gResponse = $this->client->response($this->queryAffectExpression, $variables);
        // if ($this->gResponse->hasErrors()) {
        //     $affectResponse = $this->gResponse->errors();
        // } else {
        //     $affectResponse = $this->gResponse->affectExpressions->analytics;
        //     if (isset($apiResponse[0]->affect) && isset($affectResponse[0]->affect)) {
        //         $apiResponse[0]->affect = $affectResponse[0]->affect;
        //     }
        // }
        //print_r($apiResponse);

        return $apiResponse;
    }

    /**
     * Objective: TAP query to tokenise the text
     *            check if the string is unchanged
     *            yes: if unchanged skip call to athnor to retrieve tags
     *            no:  Retrieve Tags from Athanor
     * @param $data : text input
     * @return array
     * @throws \Exception
     */
    public function preProcess($data)
    {
        $results = array();
        $tokenisedText = $this->tapTokeniser($data);
        $alreadyTapped = isset($data['currentFeedback']['tap']) ? $data['currentFeedback']['tap'] : array();
        $loop = count($alreadyTapped) > 0 ? true : false;
        $key = false;
        if (count($tokenisedText) > 0) {
            //now go through each text and analyse
            foreach ($tokenisedText as $txt) {
                $responseTxt = new \stdClass;
                $responseTxt->str = $txt->original;
                if ($loop) {
                    $key = array_search($responseTxt->str, array_column($alreadyTapped, 'str'));
                }
                if ($key) {
                    $responseTxt->raw_tags = $alreadyTapped[$key]['raw_tags'];
                    $responseTxt->tags = $alreadyTapped[$key]['tags'];
                } else {
                    $tags = $this->rethoMoves($txt->original, $data['extra']['grammar']);
                    $responseTxt->raw_tags = count($tags) > 0 ? $tags : array();
                    $responseTxt->tags = implode(', ', $tags);
                }
                $results[] = $responseTxt;
            }
        }

        return $results;
    }

    /**
     * Based on tap tokenising
     * @param $request
     * @return array
     * @throws \Exception*
     */
    protected function tapTokeniser($request)
    {
        $splitTxt = array();
        $variables = new \stdClass();
        $variables->input = $this->cleanText($request['txt']);
        //$variables->input = $request['txt'];
        $this->gResponse = $this->client->response($this->queryTokenise, $variables);
        if ($this->gResponse->hasErrors()) {
            dd($this->gResponse->errors());
        } else {
            $splitTxt = $this->gResponse->annotations->analytics;
        }
        return $splitTxt;
    }

    /**
     * Get from Athanor Rhetorical moves
     * @param $text
     * @param $grammar
     * @return array
     * @throws \Exception
     */
    protected function rethoMoves($text, $grammar)
    {
        $apiResponse = new \StdClass();
        $variables = new \stdClass();
        // $variables->input = strip_tags($text);
        $variables->input = $this->cleanText($text);
        $params = new \StdClass();
        $tags = array();
        //get athanor rethmoves
        if ($grammar == 'reflective') {
            $params->grammar = "reflective";
            $variables->parameters = json_encode($params);
            $this->gResponse = $this->client->response($this->queryMoves, $variables);
        } elseif ($grammar == 'analytical') {
            $params->grammar = "analytic";
            $variables->parameters = json_encode($params);
            $this->gResponse = $this->client->response($this->queryMoves, $variables);

            //$this->gResponse = $this->client->response($this->queryOneA, $variables);
        }

        //$this->gResponse = $this->client->response($this->queryMoves, $variables);

        if ($this->gResponse->hasErrors()) {
            dd($this->gResponse->errors());
        } else {
            $raw_tags = $this->gResponse->moves->analytics;
            foreach ($raw_tags as $tag) {
                $tags = $tag;
            }
        }

        return $tags;
    }

    protected function cleanText($string)
    {
        $replace = Html2Text::convert($string, true);
        $output = str_replace("\n", "[&]", $replace);
        return $output;
    }
}
