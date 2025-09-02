<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function attendance(){
        return view('studentattendance');
    }
    public function uploadfeedback(){
        return view('uploadfeedback');
    }
    
    public function uploadmedia(){
        return view('uploadmedia');
    }
}
