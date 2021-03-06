<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\TradFtHeadcountByTypes;
use App\Recipient;

class ReportController extends Controller
{
  public function send()
  {
      // dd('inside ReportController@send');
      $term = '20201';

      // email report recipients
      $recipients = Recipient::all();
      // dd($to);

      $to = [];

      foreach($recipients as $recipient)
      {
        array_push($to, ['name' => $recipient->first_name . ' ' . $recipient->last_name,
                     'email' => $recipient->email]);
      }

      // dd($to);

      // $to = array(
      //   ['name' => first1 last1, 'email' => email1],
      // );

      // put Empower query builder queries here??
      // Need to calculate each of the headcount elements!!!
      // $data = array(
      //   'data11'  => 65, 'data12'  => 105, 'data13'  => 170,
      //   'data21'  => 68, 'data22'  => 31, 'data23'  => 99,
      //   'data31'  => 9, 'data32'  => 7, 'data33'  => 16,
      //   'data41'  => 142, 'data42'  => 143, 'data43'  => 285,
      // );

      TradFtHeadcountByTypes::dispatch($term, $to);
  }
}
