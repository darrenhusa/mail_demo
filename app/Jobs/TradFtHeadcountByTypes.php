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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $term = '20201';

      // email report recipients
      $to = array(
        ['name' => 'Johnny Craig', 'email' => 'jcraig@ccsj.edu'],
        ['name' => 'Lynn Miskus', 'email' => 'lmiskus@ccsj.edu'],
        ['name' => 'Andy Marks', 'email' => 'amarks@ccsj.edu'],
        ['name' => 'Dionne Jones-Malone', 'email' => 'djonesmalone@ccsj.edu'],
      );

      // put Empower query builder queries here??
      // Need to calculate each of the headcount elements!!!
      $data = array(
        'data11'  => 65, 'data12'  => 105, 'data13'  => 170,
        'data21'  => 68, 'data22'  => 31, 'data23'  => 99,
        'data31'  => 9, 'data32'  => 7, 'data33'  => 16,
        'data41'  => 142, 'data42'  => 143, 'data43'  => 285,
      );

      Mail::to($to)
          ->send(new FtTradHeadcountByTypes($term, $data));

      return redirect('/')
        ->with('message', 'Email sent!');
    }
}
