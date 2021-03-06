<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Queries\TradEnrolled;
use App\Queries\FtTradHeadcountsByTypes;

Route::get('/', function () {
    return view('welcome');
});

// test route!
Route::get('/new', function () {
    $term = '20201';
    // $studentId = '100110307';

    $results = FtTradHeadcountsByTypes::get($term);

    dd($results);
    // dd($sql, $results, $count);

    // return view('welcome');
});

// test route!
Route::get('/retention', 'TradRetentionReportController@index');
Route::get('/retention/{studentId}', 'TradRetentionReportController@show');

//Send Fall 2020 FtTradHeadcountByTypes Email (to mailtrap.io)
Route::get('/send', 'ReportController@send');

//Display the report recipients for the email report above
Route::get('/recipients', 'RecipientsController@index');

//Display Fall 2020 FT Trad Enrolled Students with pagination
Route::get('/students', 'TradReportController@index');
