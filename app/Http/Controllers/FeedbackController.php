<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\StoreDrafts;
use EUAutomation\GraphQL\Client;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;




class FeedbackController extends Controller
{

    public $result;
    public $graphQLURL = "http://tap-test.utscic.edu.au/graphql";
    public $client;


    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->client = new Client($this->graphQLURL);
        $this->result = new \stdClass();
    }

    public function generateFeedback(Request $request) {
        $tap = $request["tap"];


        if(count($tap) > 0 ) {
            $this->result->temporality = array();
            $this->result->temporality['message'] ='';
            $tempo =0;
            foreach($tap as $key => $data) {
                if(in_array($key, array(0,1,2))
                    && count($data['raw_tags']) > 0
                ) {
                   if(in_array('temporality', $data['raw_tags'])) {
                       $tempo++;
                   }
                }
            }
            if($tempo == 0) $this->result->temporality['message'] = 'Background information missing in first paragraph';
        }

        return response()->json($this->result);


    }

}
