<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Draft;


class StoreDrafts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $draft;

    public function __construct($draft)
    {
        $this->draft = $draft;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $draftNew->text_input = $this->draft->original_text;
        $draftNew->feature_id = 1;
        $draftNew->assignment_id = $this->draft->assignment_id;
        $draftNew->raw_response = json_encode($this->draft->response);
        $draftNew->user_id = $this->draft->user_id;
        $draftNew = new Draft();


        $draftNew->save();

        Log::info('Stored draft into db',['draft' => $draftNew]);

    }
}
