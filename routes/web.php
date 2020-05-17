<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMe;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send', function () {

    Mail::to('jcraig@ccsj.edu')
        ->send(new ContactMe('shirts'));

    // Mail::raw('It works!', function ($message) {
    //   $message->to('jcraig@ccsj.edu')
    //       ->subject('ft trad headcount by entry type');
    // });

    return redirect('/')
      ->with('message', 'Email sent!');

    // return 'Email sent!';
});
