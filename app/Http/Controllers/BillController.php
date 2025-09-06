<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    public function uploadbills(){
        return view('uploadbills');
    }
    public function uploadtravelbills(){
        return view('uploadtravelbills');
    }
    public function uploadexpensebills(){
        return view('uploadexpensebills');
    }
}
