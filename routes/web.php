<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\FtTradHeadcountByTypes;
// use App\Jobs\TradFtHeadcountByTypes;
// use App\Queries\SrAthletes;
// use App\Queries\AtAthletes;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/example1', function () {
    $query = \DB::connection('odbc')
    ->table('CCSJ_PROD.SR_STUDENT_TERM')
    ->select('NAME_ID', 'CDIV_ID')
    ->where('TERM_ID', '=', '20201');

    $sql = $query->toSql();
    $results = $query->get();
    $count = $query->count();

    dd($sql, $results, $count);

    // return view('welcome');
});

Route::get('/example2', function () {
    $query = \DB::connection('odbc')
    ->table('CCSJ_PROD.SR_STUDENT_TERM')
    ->select('TERM_ID', 'DFLT_ID', 'LAST_NAME', 'FIRST_NAME', 'CDIV_ID')
    ->join('CCSJ_PROD.CCSJ_CO_V_NAME',
           'CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID', '=',
           'CCSJ_PROD.SR_STUDENT_TERM.NAME_ID')
    ->where('TERM_ID', '=', '20201')
    ->where('DFLT_ID', '=', '100124780');

    $sql = $query->toSql();
    $results = $query->get();
    // $count = $query->count();

    dd($sql, $results);
    // dd($sql, $results, $count);

    // return view('welcome');
});

Route::get('/lookup-at-athlete-status', function () {

    $termId = '20201';

    //beyonce hampton - does not have any 20201 record!!!
    $studentId = '100124780';
    // count = 0

    // Kevin McCune has TWO AT-sports! MBO and MBS!!!
    $studentId = '100110307';
    // count = 2

    // Hannah Gentry has ONE AT-sport WSO!!!
    $studentId = '100116464';
    // count = 1

    // Andres Gomez is a PB - this is a non-athlete sport!!!
    // Need to join the remaining table to FILTER out records like this one!


    //Access query builder results
    // SELECT
    //     CCSJ_PROD_AT_ADMI_TERM.TERM_ID,
    //     CCSJ_PROD_AT_ADMI_TERM.ADST_ID,
    //     CCSJ_PROD_AT_ADMI_TERM.ETYP_ID,
    //     CCSJ_PROD_CCSJ_CO_V_NAME.DFLT_ID,
    //     CCSJ_PROD_CCSJ_CO_V_NAME.LAST_NAME,
    //     CCSJ_PROD_CCSJ_CO_V_NAME.FIRST_NAME,
    //     CCSJ_PROD_AT_ADMI_ACTIV.ACTI_ID,
    //     CCSJ_PROD_CO_ACTIV_CODE.ATHLETIC_FLAG
    // FROM ((CCSJ_PROD_CCSJ_CO_V_NAME
    // INNER JOIN
    // CCSJ_PROD_AT_ADMI_TERM ON
    // CCSJ_PROD_CCSJ_CO_V_NAME.NAME_ID =
    // CCSJ_PROD_AT_ADMI_TERM.NAME_ID)
    // INNER JOIN
    // CCSJ_PROD_AT_ADMI_ACTIV ON
    // CCSJ_PROD_AT_ADMI_TERM.NAME_ID =
    // CCSJ_PROD_AT_ADMI_ACTIV.NAME_ID)
    // INNER JOIN
    // CCSJ_PROD_CO_ACTIV_CODE ON
    // CCSJ_PROD_AT_ADMI_ACTIV.ACTI_ID =
    // CCSJ_PROD_CO_ACTIV_CODE.ACTI_ID
    // WHERE (((CCSJ_PROD_AT_ADMI_TERM.TERM_ID)="20201") AND
    // ((CCSJ_PROD_CCSJ_CO_V_NAME.DFLT_ID)="100124780") AND
    // ((CCSJ_PROD_CO_ACTIV_CODE.ATHLETIC_FLAG)="T"));

    $query = \DB::connection('odbc')
    ->table('CCSJ_PROD.AT_ADMI_TERM')
    // ->select('TERM_ID', 'ADST_ID', 'ETYP_ID', 'DFLT_ID', 'LAST_NAME', 'FIRST_NAME', 'ACTI_ID', 'ATHLETIC_FLAG')
    ->select('TERM_ID', 'ADST_ID', 'ETYP_ID', 'DFLT_ID', 'LAST_NAME', 'FIRST_NAME', 'ACTI_ID')
    ->where('TERM_ID', '=', $termId)
    ->join('CCSJ_PROD.CCSJ_CO_V_NAME', function ($join) {
        $join->on('CCSJ_PROD.CCSJ_CO_V_NAME.NAME_ID', '=', 'CCSJ_PROD.AT_ADMI_TERM.NAME_ID');
    })
    // ->where('DFLT_ID', '=', $studentId)
    // ->where('ACTI_ID', '=', 'PB')
    ->join('CCSJ_PROD.AT_ADMI_ACTIV',
            'CCSJ_PROD.AT_ADMI_TERM.NAME_ID', '=',
            'CCSJ_PROD.AT_ADMI_ACTIV.NAME_ID');
    // ->join('CCSJ_PROD.AT_ADMI_ACTIV',
    //         'CCSJ_PROD.AT_ADMI_TERM.ACTI_ID', '=',
    //         'CCSJ_PROD.CCSJ_PROD_CO_ACTIV_CODE.ACTI_ID');

    $sql = $query->toSql();
    // $numAtSports = $query->count();
    $results = $query->get();
    $count = $query->count();

    dd($sql, $count);
    // dd($numAtSports, $results);
    // dd($sql, $results, $count);

    // return view('welcome');
});


//Send Fall 2020 FtTradHeadcountByTypes Email (to mailtrap.io)
Route::get('/send', 'ReportController@send');

//Display the report recipients for the email report above
Route::get('/recipients', 'RecipientsController@index');

//Display Fall 2020 FT Trad Enrolled Students with pagination
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
