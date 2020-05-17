<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ContactMe extends Mailable
{
    use Queueable, SerializesModels;

    public $topic;
    public $total_actors;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $topic)
    {
        // query db for all actors

        // dd(DB::table('actor')->count());
        $this->total_actors = DB::table('actor')->count();
        // $this->total_actors = 32;
        $this->topic = $topic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact-me')
            ->subject('More information about ' . $this->topic);
    }
}
