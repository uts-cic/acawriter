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

    protected $data;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $textDraft = new TextDraft();
        $textDraft->text_input = '' . $this->data['txt'];
        $textDraft->feature_id = $this->data['extra']['feature'];
        $textDraft->document_id = $this->data['document'];
        $textDraft->user_id = $this->user->id;
        $textDraft->save();

        //emit activity
        $activityLog = new \stdClass;
        $activityLog->status = $textDraft->id ? 'success' : 'error';
        $activityLog->data = [];
        $activityLog->msg = "Draft saved";
        $activityLog->user = $this->user;
        $activityLog->type = 'Draft';
        $activityLog->ref = $textDraft;
        $activityLog->jobRef = $this->data['extra']['storeDraftJobRef'];

        event(new UserActivity($this->user, $activityLog));

        Log::info('Stored draft with only text into db', ['draft' => $textDraft]);
    }
}
