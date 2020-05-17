<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use App\Mail\FtTradHeadcountByTypes;

class TradFtHeadcountByTypes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $term;
    private $to;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($term, $to, $data)
    {
        $this->term = $term;
        $this->to = $to;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      Mail::to($this->to)
          ->send(new FtTradHeadcountByTypes($this->term, $this->data));

      // return redirect('/')
      //   ->with('message', 'Email sent!');
    }
}
