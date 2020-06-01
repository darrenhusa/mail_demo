<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipient;


class RecipientsController extends Controller
{
    public function index()
    {
      $recipients = Recipient::get();
      dd($recipients);
    }
}
