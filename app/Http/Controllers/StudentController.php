<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function attendance(){
        return view('studentattendance');
    }

    public function trainingphotos(){
        return view('trainingphotos');
    }
    public function trainingvideos(){
        return view('trainingvideos');
    }
    public function trainingcompcertificate(){
        return view('trainingcompcertificate');
    }

    public function writtenfeedback(){
        return view('writtenfeedback');
    }

    public function uploadfeedback(){
        return view('uploadfeedback');
    }
    
    public function uploadmedia(){
        return view('uploadmedia');
    }
}
