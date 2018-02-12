<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Http\Controllers\StringTokenizer;
use App\Draft;
use App\Feature;
use App\User;
//use App\Events\OperationLog;
use App\Events\UserActivity;


class StoreDrafts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $draft;
    protected $stringTokeniser;
    private $metricsWordLength = 25;
    private $para = 3;
    private $user;

    public function __construct($draft, $user)
    {
        $this->draft = $draft;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //emit activity
        $activityLog = new \stdClass;
        $activityLog->status = 'success';
        $activityLog->data =[];
        // get feedback
        $this->stringTokeniser = new StringTokenizer();
        $tap = $this->stringTokeniser->preProcess($this->draft);
        Log::info('Drafts',['tokeniser' =>'completed'.date('d/m/y:H:i:s') ]);

        $result = new \stdClass();
        $extra = $this->draft["extra"];
        $result->status = array('message' => 'Success', 'code' => 200  );
        $jobRef= $extra['storeDraftJobRef'];
        $result->rules = array();
        if($extra['feature'] > 0 ) {
            $feed = $this->getFeedbackSchema('',$extra['feature']);
            $feedbackSchema = json_decode($feed, true);
            $result->rules= $this->rules = $feedbackSchema["rules"];
        }

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

        $draftNew = new Draft();
        $draftNew->text_input = $this->draft['txt'];
        $draftNew->feature_id = $this->draft['extra']['feature'];
        $draftNew->document_id = $this->draft['document'];
        $draftNew->raw_response = json_encode($result);
        $draftNew->user_id = $this->user->id;
        $draftNew->is_auto = $this->draft['type'] == 'manual' ? 0 :1;

        $draftNew->save();



        if($draftNew->id > 0) {
            $message = "Draft stored";
            $activityLog->status= 'success';
        } else {
            $activityLog->status= 'error';
        }
        $activityLog->user = $this->user;
        $activityLog->type = 'Draft';
        $activityLog->ref = $draftNew;
        $activityLog->jobRef = $jobRef;


        event(new UserActivity($this->user, $activityLog));
        //event(new OperationLog($this->user, $message));

        Log::info('Stored draft into db',['draft' => $draftNew]);

    }

    protected function getFeedbackSchema($path, $id)
    {
        if ($path == '') {
            $features = Feature::find($id);
            $data = $features->rules;
            // print_r($data);
            $json= json_decode($data, true);
        } else {
            $json = json_decode(file_get_contents($path), true);
        }
        return $json;
    }

    protected function background($tap, $rule) {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;

        foreach($tap as $key => $data) {
            $setFeed = new \stdClass();
            $setFeed->str = $data->str;
            $setFeed->message = array();
            $setFeed->css = array();
            if ($key < $check['paragraph'] && count($data->raw_tags) > 0) {
                if (in_array($check['paragraph'], $data->raw_tags)) {
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
            $tempStore->str = $data->str;
            $tempStore->message = array();
            $tempStore->css = array();
            $returnData = $this->stringTokeniser->metrics($data->str);
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
            $completeText .= $data->str;
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
            $tempStore->str = $data->str;
            $tempStore->message = array();
            $tempStore->affect=array();
            $tempStore->epistemic=array();
            $tempStore->modal=array();
            $tempStore->css = array();

            $returnData = $this->stringTokeniser->expression($data->str);
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
            $setFeed->str = $data->str;
            $setFeed->message = array();
            $setFeed->css = array();

            foreach($tags as $tag) {
                if(count(preg_grep("[^".$tag."]", $data->raw_tags)) > 0) {
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
            $newLn=str_replace("[&][&]", "<br />", $raw->str);
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
        Log::info('store',['feedback' =>'completed'.date('d/m/y:H:i:s') ]);
        return $final;
    }
}
