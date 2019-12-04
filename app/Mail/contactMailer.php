<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Crm;

class contactMailer extends Mailable
{
    use Queueable, SerializesModels;

    protected $crm;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Crm $crm)
    {
        $this->crm = $crm;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email')
            ->with([
                'name' => $this->crm->name,
                'comment' => $this->crm->comment,
                'email' => $this->crm->email
            ]);
    }
}
