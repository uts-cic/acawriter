<?php
/**
 * Copyright (c) 2018 original UTS CIC. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * UTS Connected Intelligence Centre
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Events\UserActivity;
use App\TextDraft;
use App\User;


class StoreDraftWithoutFeedback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $draft;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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

        $result = new \stdClass();
        $extra = $this->draft["extra"];
        $result->status = array('message' => 'Success', 'code' => 200  );
        $jobRef= $extra['storeDraftJobRef'];

        $draftNew = new TextDraft();
        $draftNew->text_input = $this->draft['txt'];
        $draftNew->feature_id = $this->draft['extra']['feature'];
        $draftNew->document_id = $this->draft['document'];
        $draftNew->user_id = $this->user->id;

        $draftNew->save();



        if($draftNew->id > 0) {
            $message = "Draft stored";
            $activityLog->status= 'success';
        } else {
            $activityLog->status= 'error';
        }
        $activityLog->msg = "Text Saved";
        $activityLog->user = $this->user;
        $activityLog->type = 'Draft';
        $activityLog->ref = $draftNew;
        $activityLog->jobRef = $jobRef;

        print_r($activityLog);

        event(new UserActivity($this->user, $activityLog));

        Log::info('Stored draft with only text into db',['draft' => $draftNew]);

    }
}
