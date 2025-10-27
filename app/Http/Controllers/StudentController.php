<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Models\District;
use App\Models\School;
use App\Models\StudentMst;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class StudentController extends Controller
{
    public function addstudent(){

        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $districtID= User::select('district_id')->where('id', $userId )->get('district_id');
        if ($roleId == 1 || $roleId == 2){
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        }else{
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id',$districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        // $schools = School::select('scm_id', 'scm_name')->where('scm_dist_id', Auth::user()->district_id)->orderBy('scm_name', 'asc')->get();

        return view('addstudent', compact('schools'));
    }
    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
            'school' => 'required'
        ]);

         // Get logged-in user's district
        $userId = Auth::id();
        $district = User::where('id', $userId)->value('district_id');

        // Get selected school info
        $school = School::select('scm_id', 'scm_name', 'scm_udise_code')
                        ->where('scm_id', $request->school)
                        ->first();

        Excel::import(new StudentsImport($district, $school), $request->file('file'));

        return back()->with('success', 'Students imported successfully!');
    }
    
    public function studentlist(Request $request){

        $userId   = Auth::id();
        $roleId = Auth::user()->role_id;
        
        $districtID= User::select('district_id')->where('id', $userId )->get('district_id');
        
        $schoolId = $request->input('school_id');

        $studentsQuery = StudentMst::orderBy('stu_class')
            ->orderBy('stu_section');

        if ($schoolId) {
            $studentsQuery->where('stu_scm_id', $schoolId);
        } else {
            // Show empty list initially if no school is selected
            $studentsQuery->whereNull('stu_scm_id');
        }

        $students = $studentsQuery->get();

        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        if ($roleId == 1 || $roleId == 2){
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        }else{
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id',$districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        return view('studentlist', compact('students', 'schools', 'schoolId'));
    }

    public function getStudentsBySchool($schoolId)
    {
        $students = StudentMst::where('stu_schoolname', $schoolId)
            ->orderBy('stu_class')
            ->orderBy('stu_section')
            ->get();

        return response()->json($students);
    }
    public function addstudentsin() {

        $userId   = Auth::id();
        $roleId = Auth::user()->role_id;

        $districtID= User::select('district_id')->where('id', $userId )->get('district_id');
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        if ($roleId == 1 || $roleId == 2){
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        }else{
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id',$districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        // $schools = School::select('scm_id', 'scm_name', 'scm_udise_code')->where('scm_dist_id',$districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        return view('addstudentsin',compact( 'schools')); // loads add student form
    }

public function store(Request $request) {
    $userId = Auth::id();

    $districtID= User::select('district_id')->where('id', $userId )->get('district_id');    
    // $Udise= School::select('scm_udise_code')->where('scm_id', $request->stu_schoolname )->get('scm_udise_code');
    $school= School::select('scm_name', 'scm_udise_code')->where('scm_id', $request->stu_schoolname )->first();

    $validated = $request->validate([
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
        'stu_address' => 'required',
    ]);


    $validated['stu_schoolname'] = $school->scm_name;
    $validated['stu_scm_udise'] = $school->scm_udise_code;
    $validated['stu_distid'] = $districtID[0]->district_id;

    $validated['stu_scm_id'] = $request->stu_schoolname;
    
    StudentMst::create($validated);

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
    $userId = Auth::id();

    $districtID= User::select('district_id')->where('id', $userId )->get('district_id');    
    // $Udise= School::select('scm_udise_code')->where('scm_id', $request->stu_schoolname )->get('scm_udise_code');
    $school= School::select('scm_name', 'scm_udise_code')->where('scm_id', $request->stu_schoolname )->first();

    $validated = $request->validate([
        'stu_name' => 'required',
        'stu_roll_number' => 'required',
        'stu_class' => 'required',
        'stu_section' => 'required',
        'stu_gender' => 'required',
        'stu_dob' => 'required|date',
        'stu_fathername' => 'required',
        'stu_address' => 'required',
        'stu_schoolname' => 'required',
        'stu_address' => 'required',
    ]);

    $validated['stu_schoolname'] = $school->scm_name;
    $validated['stu_scm_udise'] = $school->scm_udise_code;

    $validated['stu_scm_id'] = $request->stu_schoolname;

    $student->update($validated);

    return response()->json(['success' => true, 'message' => 'Student updated successfully!']);
}

    public function destroy($id) {
        $student = StudentMst::findOrFail($id);
        $student->delete();
        return redirect()->route('studentlist')->with('success', 'Student deleted successfully.');
    }

    
    
    

    public function onlinefeedback(){
        return view('onlinefeedback');
    }
    
    public function uploadmedia(){
        return view('uploadmedia');
    }
}
