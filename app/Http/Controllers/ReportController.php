<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function uploadreport(){
        return view('uploadreport');
    }
}
