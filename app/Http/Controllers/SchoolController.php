<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\School;
use App\Models\StudentMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;

class SchoolController extends Controller
{
    public function index()
    {
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')
            ->orderBy('DSM_DSNM', 'asc')->get();

        $totalSchools = School::select('scm_dist_id', DB::raw('COUNT(*) as total'))
            ->groupBy('scm_dist_id')
            ->pluck('total', 'scm_dist_id');

        $trainingCounts = School::select('scm_dist_id', DB::raw('COUNT(*) as completed_training'))
        ->where('training_completed', 1)
        ->groupBy('scm_dist_id')
        ->pluck('completed_training', 'scm_dist_id');

        $currentRoute = Route::currentRouteName();

        // ✅ Choose blade file based on route name
        if ($currentRoute === 'select.district') {
            return view('schoollist.selectdistrict', compact('districts', 'totalSchools', 'trainingCounts'));
        }

        if ($currentRoute === 'student.school') {
            return view('school.districtschool', compact('districts', 'totalSchools', 'trainingCounts'));
        }

    }

    public function getSchools($id)
    {

        $schools = School::where('scm_dist_id', $id)
            ->select('scm_id', 'scm_name', 'training_completed')
            ->orderBy('scm_name', 'asc')
            ->withCount([
                'students as student_count' => function ($q) {
                    $q->select(DB::raw("count(*)"));
                }
            ])
            ->get();

        $totalStudents = $schools->sum('student_count');

        return response()->json([
            'schools' => $schools,
            'total_students' => $totalStudents
        ]);
    }

    public function getStudentsPage($id)
    {
        $school = School::where('scm_id', $id)->firstOrFail();

        $students = StudentMst::where('stu_scm_id', $id)
            ->select('stu_id', 'stu_name', 'stu_roll_number', 'stu_class', 'stu_fathername')
            ->orderBy('stu_name', 'asc')
            ->get();

        return view('school.school_students', compact('school', 'students'));
    }


    public function districtSchoolList($id)
    {
        $district = District::select('DSM_DSCD', 'DSM_DSNM')->findOrFail($id);
    
        // Get all schools for this district
        $schools = School::where('scm_dist_id', $id)
            ->select('scm_id', 'scm_name', 'scm_udise_code')
            ->withCount('students')
            ->get();
        
        return view('school.schoollist', compact('district', 'schools'));
        
    }
    public function selectdistrictList($id)
    {
        $district = District::select('DSM_DSCD', 'DSM_DSNM')->findOrFail($id);
    
        // Get all schools for this district
        $schools = School::where('scm_dist_id', $id)
            ->select('scm_id', 'scm_name', 'scm_udise_code', 'training_completed')
            ->withCount(['students', 'coordinators', 'trainers', 'staffs'])
            ->get();
    
        return view('schoollist.selectschool', compact('district', 'schools'));

    }
    public function schoolCoordinatorsJson($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $coordinators = $school->coordinators()->select('coordinator_name', 'phone', 'email', 'photo')->get();

        return response()->json($coordinators);
    }
    public function schoolTrainersJson($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $trainers = $school->trainers()->select('trainer_name', 'phone', 'email', 'photo')->get();

        return response()->json($trainers);
    }
    public function schoolStaffsJson($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $staffs = $school->staffs()->select('ss_name', 'phone', 'email', 'photo')->get();

        return response()->json($staffs);
    }

}
