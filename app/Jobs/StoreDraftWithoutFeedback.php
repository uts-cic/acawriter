<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\UserActivity;
use App\TextDraft;

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
        $activityLog->data = [];

        $result = new \stdClass();
        $extra = $this->draft["extra"];
        $result->status = array('message' => 'Success', 'code' => 200);
        $jobRef = $extra['storeDraftJobRef'];

        $draftNew = new TextDraft();
        $draftNew->text_input = $this->draft['txt'];
        $draftNew->feature_id = $this->draft['extra']['feature'];
        $draftNew->document_id = $this->draft['document'];
        $draftNew->user_id = $this->user->id;

        $draftNew->save();

        if ($draftNew->id > 0) {
            $activityLog->status = 'success';
        } else {
            $activityLog->status = 'error';
        }
        $activityLog->msg = "Draft saved";
        $activityLog->user = $this->user;
        $activityLog->type = 'Draft';
        $activityLog->ref = $draftNew;
        $activityLog->jobRef = $jobRef;

        //print_r($activityLog);

        event(new UserActivity($this->user, $activityLog));

        Log::info('Stored draft with only text into db', ['draft' => $draftNew]);
    }
}
