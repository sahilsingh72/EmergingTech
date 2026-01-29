<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\InstituteFeedback;
use App\Models\School;
use App\Models\StudentFeedback;
use App\Models\StudentMst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->select('stu_id', 'stu_name', 'stu_roll_number', 'stu_class', 'stu_fathername', 'stu_gender')
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
            ->select('scm_id', 'scm_name', 'scm_udise_code', 'training_completed', 'training_date')
            ->withCount(['students', 'coordinators', 'trainers', 'staffs'])
            ->get();

        return view('schoollist.selectschool', compact('district', 'schools'));
    }
    public function saveTrainingDate(Request $request, $id)
    {
        $request->validate([
            'training_date' => 'required|date',
        ]);

        $school = School::findOrFail($id);
        $school->training_date = $request->training_date;  // Column name in DB
        $school->save();

        return response()->json(['success' => true]);
    }

    public function schoolDetailsJson($schoolId)
    {
        $school = School::where('scm_id', $schoolId)->first();

        $studentCount = StudentMst::where('stu_scm_id', $schoolId)->count();

        $school->students_count = $studentCount;

        return response()->json($school);
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
        $trainers = $school->trainers()->select('trainer_name', 'phone', 'email', 'photo', 'specialization')->get();

        return response()->json($trainers);
    }
    public function schoolStaffsJson($schoolId)
    {
        $school = School::findOrFail($schoolId);
        $staffs = $school->staffs()->select('ss_name', 'phone', 'email', 'photo')->get();

        return response()->json($staffs);
    }

    public function showSchoolData($schoolId)
    {
        $school = School::with([
            'trainingUploads',
        ])->findOrFail($schoolId);
    
        $totalStudents = StudentMst::where('stu_scm_id', $schoolId)
            ->where('attendance', 1)
            ->count();

        $instituteFeedbackEntrys = InstituteFeedback::where('school_id', $schoolId)->get();

        $studentsWithFeedbackEntrys =StudentFeedback::where('school_id', $schoolId)
            ->whereIn('stu_id', function ($q) use ($schoolId) {
                $q->select('stu_id')
                    ->from('student_mst')
                    ->where('stu_scm_id', $schoolId)
                    ->where('attendance', 1);
            })->get();

        return view('schoollist.schooldatashow', compact('school', 'totalStudents', 'instituteFeedbackEntrys', 'studentsWithFeedbackEntrys'));
    }

    public function mySchools()
    {
        $user = Auth::user();


        $districtId = $user->district_id;

        $district = District::select('DSM_DSCD', 'DSM_DSNM')->findOrFail($districtId);

        $schools = School::where('scm_dist_id', $districtId)
            ->get();

        return view('dlc.schoollist', compact('schools', 'district'));
    }
    public function schoolDetails($id)
    {
        $school = School::findOrFail($id);

        return view('dlc.schooldetails', compact('school'));
    }

    public function updateSchool(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);

        // Validate fields
        $request->validate([
            'scm_hm_name' => 'nullable|string|max:255',
            'scm_hm_phone' => 'nullable|string|max:20',
            'scm_hm_wp'    => 'nullable|string|max:20',
            'scm_hm_email' => 'nullable|email|max:255',

            'scm_spoc_name' => 'nullable|string|max:255',
            'scm_spoc_phone' => 'nullable|string|max:20',
            'scm_spoc_wp'    => 'nullable|string|max:20',
            'scm_spoc_email' => 'nullable|email|max:255',

            'scm_avail_3_class' => 'required|string',
            'scm_smartclass' => 'nullable|integer|min:0',
            'scm_powerbackup' => 'required|string',
            'scm_internet' => 'required|string',

            'scm_address' => 'nullable|string|max:500',
        ]);

        // Update school details
        $school->update([
            'scm_hm_name'      => $request->scm_hm_name,
            'scm_hm_phone'     => $request->scm_hm_phone,
            'scm_hm_wp'        => $request->scm_hm_wp,
            'scm_hm_email'     => $request->scm_hm_email,

            'scm_spoc_name'    => $request->scm_spoc_name,
            'scm_spoc_phone'   => $request->scm_spoc_phone,
            'scm_spoc_wp'      => $request->scm_spoc_wp,
            'scm_spoc_email'   => $request->scm_spoc_email,

            'scm_avail_3_class' => $request->scm_avail_3_class === 'Yes' ? 1 : 0,
            'scm_powerbackup'   => $request->scm_powerbackup === 'Yes' ? 1 : 0,
            'scm_powerbackup_type'  => $request->scm_powerbackup === 'Yes' ? $request->scm_powerbackup_type : null,
            'scm_internet'      => $request->scm_internet === 'Yes' ? 1 : 0,
            'scm_internet_type' => $request->scm_internet === 'Yes' ? $request->scm_internet_type : null,
            'scm_smartclass'    => $request->scm_smartclass,

            'scm_address'       => $request->scm_address,
        ]);

        return back()->with('success', 'School details updated successfully!');
    }

    public function schoolList()
    {
        $schools = School::all();
        $studentCounts = StudentMst::select('stu_scm_id', DB::raw('COUNT(*) as total_students'))
            ->groupBy('stu_scm_id')
            ->pluck('total_students', 'stu_scm_id');
        $trainerCounts = DB::table('trainer_scm_allocation')
            ->select('scm_id', DB::raw('COUNT(trainer_id) as total_trainers'))
            ->groupBy('scm_id')
            ->pluck('total_trainers', 'scm_id');
        $coordinatorCounts = DB::table('coordinator_scm_allocation')
            ->select('scm_id', DB::raw('COUNT(coordinator_id) as total_coordinators'))
            ->groupBy('scm_id')
            ->pluck('total_coordinators', 'scm_id');
        $supportStaffCounts = DB::table('staff_scm_allocation')
            ->select('scm_id', DB::raw('COUNT(staff_id) as total_staffs'))
            ->groupBy('scm_id')
            ->pluck('total_staffs', 'scm_id');  

        return view('schoollist.mainSchoollist', compact('schools', 'studentCounts', 'trainerCounts', 'coordinatorCounts', 'supportStaffCounts'));
    }
    public function trainingCompletedDistList(){
        // $districts = District::select('DSM_DSCD', 'DSM_DSNM')
        //     ->orderBy('DSM_DSNM', 'asc')->get();

        $totalSchools = School::select('scm_dist_id', DB::raw('COUNT(*) as total'))
            ->groupBy('scm_dist_id')
            ->pluck('total', 'scm_dist_id');

        // $trainingCounts = School::select('scm_dist_id', DB::raw('COUNT(*) as completed_training'))
        //     ->where('training_completed', 1)
        //     ->groupBy('scm_dist_id')
        //     ->pluck('completed_training', 'scm_dist_id');
        $districts = District::whereHas('schools.trainingUploads', function ($q) {
                $q->where('file_type', 'attendance_sheet');
            })
            ->select('DSM_DSCD', 'DSM_DSNM')
            ->orderBy('DSM_DSNM', 'asc')
            ->get();

        return view('schoollist.trainingcompleteddistlist', compact('districts', 'totalSchools'));
    }
    public function districtSchools($districtId)
    {
        $schools = School::where('scm_dist_id', $districtId)
            ->whereHas('trainingUploads', function ($q) {
                $q->whereIn('file_type', ['attendance_sheet']);
            })
            ->select('scm_id', 'scm_name')
            ->orderBy('scm_name')
            ->get();

        return response()->json([
            'schools' => $schools
        ]);
    }
    public function trainingCompletedSchoolList($id)
    {
        $district = District::select('DSM_DSCD', 'DSM_DSNM')->findOrFail($id);

        // Get all schools for this district
        $schools = School::where('scm_dist_id', $id)
            ->whereHas('trainingUploads', function ($q) {
                $q->where('file_type', 'attendance_sheet');
            })
            ->select('scm_id', 'scm_name', 'scm_udise_code', 'training_completed', 'training_date')
            ->withCount(['students', 'coordinators', 'trainers', 'staffs'])
            ->get();

        return view('schoollist.trainingcompletedschool', compact('district', 'schools'));
    }

}