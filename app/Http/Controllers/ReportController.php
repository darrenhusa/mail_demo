<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\TradFtHeadcountByTypes;

class ReportController extends Controller
{
  public function send()
  {
      // dd('inside ReportController@send');

      TradFtHeadcountByTypes::dispatch();
  }
}
