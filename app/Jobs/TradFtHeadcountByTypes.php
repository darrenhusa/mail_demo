<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

// use App\Mail\FtTradHeadcountByTypes;
// use App\Queries\FtTradHeadcountsByTypes;

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
    public function __construct($term, $to)
    {
        $this->term = $term;
        $this->to = $to;
        // $this->data = $data;

        //replace $data with live Empower query.
        // $term = '20191';
        $studentsCounts = \App\Queries\FtTradHeadcountsByTypes::get($this->term);
        // dd($studentsCounts);

        $this->data = $this->build_html_table_counts($studentsCounts);
        // dd($htmlTableCounts);
        // dd($numGrandTotal, $numCheck);
        // dd($numFirstTimeAthletes);
        // dd($numGrandTotal, $numFirstTimeAthletes, $students);
        // dd($numGrandTotal, $students);
        // dd($students);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      // dd($this->to, $this->data, $this->term);

      Mail::to($this->to)
          ->send(new \App\Mail\FtTradHeadcountByTypes($this->term, $this->data));

      // return redirect('/')->with('message', 'Email sent!');
    }

    private function build_html_table_counts($studentsCounts)
    {

      $numFirstTimeAthletes = $this->get_count_components($studentsCounts, 'first-time', 1);
      $numFirstTimeNonAthletes = $this->get_count_components($studentsCounts, 'first-time', 0);
      $numFirsTimeTotal = $numFirstTimeAthletes + $numFirstTimeNonAthletes;

      $numTransferAthletes = $this->get_count_components($studentsCounts, 'transfer', 1);
      $numTransferNonAthletes = $this->get_count_components($studentsCounts, 'transfer', 0);
      $numTransferTotal = $numTransferAthletes + $numTransferNonAthletes;

      $numContinuingAthletes = $this->get_count_components($studentsCounts, 'continuing/returning', 1);
      $numContinuingNonAthletes = $this->get_count_components($studentsCounts, 'continuing/returning', 0);
      $numContinuingTotal = $numContinuingAthletes + $numContinuingNonAthletes;

      $numAthleteTotal = $numFirstTimeAthletes + $numTransferAthletes + $numContinuingAthletes;
      $numNonAthleteTotal = $numFirstTimeNonAthletes + $numTransferNonAthletes + $numContinuingNonAthletes;
      $numGrandTotal = $studentsCounts->count();

      $results = ['data11' => $numContinuingAthletes, 'data12' => $numContinuingNonAthletes, 'data13' => $numContinuingTotal,
                  'data21' => $numFirstTimeAthletes, 'data22' => $numFirstTimeNonAthletes, 'data23' => $numFirsTimeTotal,
                  'data31' => $numTransferAthletes, 'data32' => $numTransferNonAthletes, 'data33' => $numTransferTotal,
                  'data41' => $numAthleteTotal, 'data42' => $numNonAthleteTotal, 'data43' => $numGrandTotal];

      return $results;
    }

    private function get_count_components($collection, $entry_type, $athlete_status)
    {
      return $collection
          ->where('EntryTypeAlt', $entry_type)
          ->where('IsAthlete', $athlete_status)
          ->count();
    }

}
