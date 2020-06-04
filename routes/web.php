<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\FtTradHeadcountByTypes;
// use App\Jobs\TradFtHeadcountByTypes;
// use App\Queries\SrAthletes;
// use App\Queries\AtAthletes;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send', 'ReportController@send');

Route::get('/recipients', 'RecipientsController@index');

Route::get('/students', 'TradReportController@index');

// Route::get('/sr_athletes', function() {
//
//   $results = SrAthletes::get('20191');
//   dd($results);
// });

// Route::get('/at_athletes', function() {
//
//   $results = AtAthletes::get('20191');
//   dd($results);
// });


// Route::get('/trad_ft', 'TradReportController@get_trad_ft');
// Route::get('/recipients', function() {
//   $recipients = Recipient::get();
//   dd($recipients);
// });

// Route::get('/send', function () {

    // $term = '20201';
    //
    // // email report recipients
    // $to = array(
    //   ['name' => 'Johnny Craig', 'email' => 'jcraig@ccsj.edu'],
    //   ['name' => 'Lynn Miskus', 'email' => 'lmiskus@ccsj.edu'],
    //   ['name' => 'Andy Marks', 'email' => 'amarks@ccsj.edu'],
    //   ['name' => 'Dionne Jones-Malone', 'email' => 'djonesmalone@ccsj.edu'],
    // );
    //
    // // put Empower query builder queries here??
    // // Need to calculate each of the headcount elements!!!
    // $data = array(
    //   'data11'  => 65, 'data12'  => 105, 'data13'  => 170,
    //   'data21'  => 68, 'data22'  => 31, 'data23'  => 99,
    //   'data31'  => 9, 'data32'  => 7, 'data33'  => 16,
    //   'data41'  => 142, 'data42'  => 143, 'data43'  => 285,
    // );
    //
    // Mail::to($to)
    //     ->send(new FtTradHeadcountByTypes($term, $data));
    //
    // return redirect('/')
    //   ->with('message', 'Email sent!');

    // return 'Email sent!';
// });
