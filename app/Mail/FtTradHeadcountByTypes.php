<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FtTradHeadcountByTypes extends Mailable
{
    use Queueable, SerializesModels;

    public $term;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($term, $data)
    {
        $this->term = $term;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->term, $this->data);
        // dd($this->data);

        return $this->view('emails.headcount-ft-trad-by-type');
    }
}
