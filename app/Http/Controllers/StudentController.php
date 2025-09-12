<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Models\StudentMst;
use Maatwebsite\Excel\Facades\Excel;


class StudentController extends Controller
{
    public function attendance(){
        return view('studentattendance');
    }
    public function addstudent(){
        return view('addstudent');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return back()->with('success', 'Students imported successfully!');
    }
    
    public function studentlist(){
        // Fetch all students, optionally order by class and section
        $students = StudentMst::orderBy('stu_class')
                            ->orderBy('stu_section')
                            ->get();
        // $students = StudentMst::all(); // fetch all students
        return view('studentlist', compact('students'));
    }
    public function addstudentsin() {
        return view('addstudentsin'); // loads add student form
    }

public function store(Request $request) {
    // Validate input
    $request->validate([
        'stu_name' => 'required',
        'stu_roll_number' => 'required',
        'stu_class' => 'required',
        'stu_section' => 'required',
        'stu_classid'    => 'integer',
        'stu_sectionid'  => 'integer',
        'stu_gender' => 'required',
        'stu_dob' => 'required|date',
        'stu_fathername' => 'required',
        'stu_schoolname' => 'required',
        'stu_scm_udise' => 'required',
        'stu_block' => 'required',
        'stu_dist' => 'required'
    ]);

    // Create student
    StudentMst::create($request->all());

    // Redirect back to student list with success message
    return redirect()->route('studentlist')->with('success', 'Student added successfully!');
}



    public function show($id)
{
    $student = StudentMst::findOrFail($id);
    return response()->json($student); // return JSON
}

public function edit($id)
{
    $student = StudentMst::findOrFail($id);
    return response()->json($student); // return JSON for edit modal
}

public function update(Request $request, $id)
{
    $student = StudentMst::findOrFail($id);

    $student->update($request->all()); // you may validate first

    return response()->json(['success' => true, 'message' => 'Student updated successfully!']);
}

    public function destroy($id) {
        $student = StudentMst::findOrFail($id);
        $student->delete();
        return redirect()->route('studentlist')->with('success', 'Student deleted successfully.');
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
    public function onlinefeedback(){
        return view('onlinefeedback');
    }
    
    public function uploadmedia(){
        return view('uploadmedia');
    }
}
