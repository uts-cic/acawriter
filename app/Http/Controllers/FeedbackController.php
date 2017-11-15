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


    public $graphQLURL = "http://tap-test.utscic.edu.au/graphql";
    public $client;
    private $metricsWordLength = 25;
    protected $para = 3;


    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->client = new Client($this->graphQLURL);

        $this->stringTokeniser = new StringTokenizer();
    }

    public function generateFeedback(Request $request) {
        $tap = $request["tap"];
        $result = new \stdClass();

        if(count($tap) > 0 ) {

            $result->temporality = array();
            $result->metrics = array();

            $tempo =0;
            foreach($tap as $key => $data) {

                $setFeed = new \stdClass();
                $setFeed->str = $data['str'];
                $setFeed->message = '';

                if($key < $this->para && count($data['raw_tags']) > 0) {
                   if(in_array('temporality', $data['raw_tags'])) {
                       $tempo++;
                   }
                }

                if($tempo == 0 && $key == $this->para -1) {
                    $setFeed->message = 'Background information missing in first paragraph';
                    $result->temporality[] = $setFeed;
                }

                //evaluate metrics
                $tempStore = new \stdClass();
                $tempStore->str = $data['str'];
                $tempStore->message = '';
                $returnData = $this->stringTokeniser->metrics($data['str']);
                if(isset($returnData->sentWordCounts)) {
                    //sentWordCounts is always an array e.g. [5,6] if two sentences sent here we send only one at a time though
                    if($returnData->sentWordCounts[0] > $this->metricsWordLength) {
                        $tempStore->message = 'Long sentence might disengage readers. Try breaking into smaller sentences';
                    }
                }

                $result->metrics[] = $tempStore;


            }

        }

        return response()->json($result);

    }

}
