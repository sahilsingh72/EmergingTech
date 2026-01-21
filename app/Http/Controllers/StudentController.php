<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Models\District;
use App\Models\School;
use App\Models\StudentFeedback;
use App\Models\StudentMst;
use App\Models\TrainingUpload;
use App\Models\User;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function addstudent()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('addstudent', compact('schools'));
    }
    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
            'school' => 'required'
        ]);

        try {
            // Load Excel
            $spreadsheet = IOFactory::load($request->file('file'));
            $sheet = $spreadsheet->getActiveSheet();

            // Read first row (header)
            $header = $sheet->rangeToArray('A1:D1')[0];

            // Trim spaces
            $header = array_map('trim', $header);

            // Expected Excel columns
            $expected = [
                'Student Name',
                'Gender',
                "Student's Father Name",
                'Class'
            ];

            // Validate header
            foreach ($expected as $col) {
                if (!in_array($col, $header)) {
                    return back()->with(
                        'error',
                        "⚠ Invalid Excel format! Please upload the file in the same format as the sample template."
                    );
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', '⚠ Unable to read the Excel file. Please upload a valid file.');
        }

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

    public function studentlist(Request $request)
    {
        $userId   = Auth::id();
        $roleId = Auth::user()->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        $schoolId = $request->input('school_id');

        $studentsQuery = StudentMst::orderBy('stu_name', 'asc')
            ->orderBy('stu_class');

        if ($schoolId) {
            $studentsQuery->where('stu_scm_id', $schoolId);
        } else {
            // Show empty list initially if no school is selected
            $studentsQuery->whereNull('stu_scm_id');
        }

        $students = $studentsQuery->get();

        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        return view('studentlist', compact('students', 'schools', 'schoolId'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) == 0) {
            return response()->json(['message' => 'No students selected'], 400);
        }

        StudentMst::whereIn('stu_id', $ids)->delete();

        return response()->json([
            'message' => 'Selected students deleted successfully'
        ]);
    }

    public function getStudentsBySchool($schoolId)
    {
        $students = StudentMst::where('stu_schoolname', $schoolId)
            ->orderBy('stu_class')
            ->orderBy('stu_section')
            ->get();

        return response()->json($students);
    }
    public function addstudentsin()
    {

        $userId   = Auth::id();
        $roleId = Auth::user()->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        return view('addstudentsin', compact('schools')); // loads add student form
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $districtID = User::select('district_id')->where('id', $userId)->value('district_id');

        $school = School::select('scm_name', 'scm_udise_code')->where('scm_id', $request->stu_schoolname)->first();

        //CHECK STUDENT LIMIT (130 max)
        $currentCount = StudentMst::where('stu_scm_id', $request->stu_schoolname)->count();

        if ($currentCount >= 130) {
            return back()
                ->withErrors(["limit" => "Maximum 130 students (120 students for camp and 10 students for backup) allowed per school. You already have $currentCount students."])
                ->withInput();
        }

        $validated = $request->validate([
            'stu_name' => 'required',
            'stu_roll_number' => 'nullable',
            'stu_class' => 'required',
            'stu_section' => 'nullable',
            'stu_classid'    => 'integer',
            'stu_sectionid'  => 'integer',
            'stu_gender' => 'required',
            'stu_dob' => 'nullable|date',
            'stu_fathername' => 'required',
            'stu_schoolname' => 'required',
            'stu_address' => 'nullable',
        ]);

        $validated['stu_schoolname'] = $school->scm_name;
        $validated['stu_scm_udise'] = $school->scm_udise_code;
        $validated['stu_distid'] = $districtID;
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

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $school = School::select('scm_name', 'scm_udise_code')->where('scm_id', $request->stu_schoolname)->first();

        $validated = $request->validate([
            'stu_name' => 'required',
            'stu_roll_number' => 'nullable',
            'stu_class' => 'required',
            'stu_section' => 'nullable',
            'stu_gender' => 'required',
            'stu_dob' => 'nullable|date',
            'stu_fathername' => 'required',
            'stu_schoolname' => 'required',
            'stu_address' => 'nullable',
        ]);

        $validated['stu_schoolname'] = $school->scm_name;
        $validated['stu_scm_udise'] = $school->scm_udise_code;

        $validated['stu_scm_id'] = $request->stu_schoolname;

        $student->update($validated);

        return response()->json(['success' => true, 'message' => 'Student updated successfully!']);
    }

    public function destroy($id)
    {
        $student = StudentMst::findOrFail($id);
        $student->delete();
        return redirect()->route('studentlist')->with('success', 'Student deleted successfully.');
    }

    public function studentFeedback(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        $schoolId = $request->input('school_id');

        $studentsQuery = StudentMst::orderBy('stu_class')
            ->orderBy('stu_section')
            ->orderBy('stu_name', 'asc')
            ->where('attendance', '1');

        if ($schoolId) {
            $studentsQuery->where('stu_scm_id', $schoolId);
        } else {
            // Show empty list initially if no school is selected
            $studentsQuery->whereNull('stu_scm_id');
        }

        $students = $studentsQuery->get();

        foreach ($students as $student) {
            $student->has_feedback_entry = StudentFeedback::where('stu_id', $student->stu_id)->exists();
        }

        return view('studentfeedback', compact('schools', 'students'));
    }
    public function uploadFeedback(Request $request, OneDriveService $oneDriveService)
    {
        $request->validate([
            'stu_id' => 'required|exists:student_mst,stu_id',
            'written_feedback' => 'required|mimes:pdf|max:10240',
        ]);

        $stu_id = $request->stu_id;
        $student = StudentMst::findOrFail($stu_id);

        $userId = Auth::id();
        $schoolId = $student->stu_scm_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $stuName = $student->stu_name;

        $fileTypeMap = config('filetypes');

        try {
            $file = $request->file('written_feedback');
            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = "EmergingTech/{$districtName}/{$schoolName}/written_feedback/student_{$stuName}";

            $upload = $oneDriveService->uploadDirect($file, $folder, $filename);

            // Store feedback details in DB
            $student->update([
                'feedback_file_name' => $filename,
                'feedback_file_path' => $upload['path'] ?? null,
                'feedback_file_url' => $upload['url'] ?? null,
                'feedback_uploaded_at' => now(),
            ]);
            
            // IMPORTANT: re-check training completion
            // $this->evaluateTrainingCompletion($schoolId);

            return response()->json([
                'success' => true,
                'message' => 'Feedback uploaded successfully',
                'file_name' => $filename,
                'url' => $upload['url']
            ]);
        } catch (\Exception $e) {
            Log::error('Upload Feedback Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // private function evaluateTrainingCompletion(int $schoolId): void
    // {
    //     // Required training files
    //     $requiredFiles = [
    //         'attendance_sheet',
    //         'training_photo',
    //         'training_video',
    //         'video_feedback',
    //         'training_completion_certificate'
    //     ];

    //     $uploadedFiles = TrainingUpload::where('school_id', $schoolId)
    //         ->whereIn('file_type', $requiredFiles)
    //         ->pluck('file_type')
    //         ->unique()
    //         ->toArray();

    //     $allTrainingFilesUploaded = empty(array_diff($requiredFiles, $uploadedFiles));

    //     // Student feedback check
    //     $totalStudents = StudentMst::where('stu_scm_id', $schoolId)
    //         ->where('attendance', 1)
    //         ->count();

    //     $studentsWithFeedback = StudentMst::where('stu_scm_id', $schoolId)
    //         ->where('attendance', 1)
    //         ->whereNotNull('feedback_file_url')
    //         ->count();

    //     $meetsStudentRule  =
    //         ($totalStudents >= 120 && $studentsWithFeedback === $totalStudents);

    //     School::where('scm_id', $schoolId)
    //         ->update(['training_completed' => 0]);

    //     // Final decision
    //     if ($allTrainingFilesUploaded && $meetsStudentRule) {
    //         School::where('scm_id', $schoolId)
    //             ->update(['training_completed' => 1]);
    //     }
    // }
    public function updateFeedback(Request $request, OneDriveService $oneDriveService)
    {
        $request->validate([
            'stu_id' => 'required|exists:student_mst,stu_id',
            'written_feedback' => 'required|mimes:pdf|max:10240',
        ]);

        try {
            $student = StudentMst::findOrFail($request->stu_id);
            $userId = Auth::id();
            $schoolId = $student->stu_scm_id;
            $school = School::find($schoolId);
            $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
            $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

            $stuName = $student->stu_name;

            // ✅ If old file exists, delete it from OneDrive first
            if (!empty($student->feedback_file_path)) {
                try {
                    $oneDriveService->deleteFile($student->feedback_file_path);
                } catch (\Exception $e) {
                    Log::warning("Old feedback file could not be deleted from OneDrive: " . $e->getMessage());
                    // continue even if delete fails
                }
            }

            // Upload new file
            $file = $request->file('written_feedback');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "EmergingTech/{$districtName}/{$schoolName}/written_feedback/student_{$stuName}";

            $upload = $oneDriveService->uploadDirect($file, $folder, $filename);

            // Replace existing file info in DB
            $student->update([
                'feedback_file_name' => $filename,
                'feedback_file_path' => $upload['path'] ?? null,
                'feedback_file_url' => $upload['url'] ?? null,
                'feedback_uploaded_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback file updated successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Feedback Replace Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function previewFile(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            return response('Invalid file path', 400);
        }

        // Cache OneDrive download URL for 5 minutes
        $downloadUrl = Cache::remember("onedrive_download_" . md5($path), 300, function () use ($path) {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            return $fileInfo['@microsoft.graph.downloadUrl'] ?? null;
        });

        if (!$downloadUrl) {
            return response('File not found or access denied', 404);
        }

        // Fetch file headers from OneDrive
        $response = Http::head($downloadUrl);
        $contentType = $response->header('Content-Type', 'application/octet-stream');

        // ✅ Only allow PDF files
        if (!str_contains($contentType, 'pdf')) {
            return response('Only PDF preview is supported.', 415);
        }

        // Fetch the PDF content and stream inline
        $pdfContent = Http::get($downloadUrl)->body();

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="feedback.pdf"');
    }

    public function createEntry($stu_id)
    {
        $student = StudentMst::where('stu_id', $stu_id)->firstOrFail();
        $existingFeedback = StudentFeedback::where('stu_id', $stu_id)->first();

        return view('feedback.entry', compact('student', 'existingFeedback'));
    }

    public function storeStudentFeedback(Request $request)
    {
        // Validate required fields
        $validated = $request->validate([
            'stu_id' => 'required|exists:student_mst,stu_id',

            //  Pre Feedback Required Fields
            'pre_attended_training' => 'required',
            // 'pre_if_any' => 'nullable|string',
            'pre_heard_technologies' => 'nullable',

            'pre_heard_ai' => 'nullable',
            'pre_heard_iot' => 'nullable',
            'pre_heard_cybersecurity' => 'nullable',

            'pre_know_tech' => 'required|integer|min:1|max:5',
            'pre_confidence' => 'required|integer|min:1|max:5',
            'pre_career' => 'required|integer|min:1|max:5',
            'pre_interest' => 'required|integer|min:1|max:5',
            'pre_usefulness' => 'required|integer|min:1|max:5',
            'pre_aware' => 'required|integer|min:1|max:5',
            'pre_ai_known' => 'required|integer|min:1|max:5',
            'pre_et_use' => 'required|integer|min:1|max:5',

            //  Post Feedback Required Fields
            'post_interested_course' => 'required',

            'post_knowledge_improve' => 'required|integer|min:1|max:5',
            'post_confidence_now' => 'required|integer|min:1|max:5',
            'post_engagement' => 'required|integer|min:1|max:5',
            'post_understanding' => 'required|integer|min:1|max:5',
            'post_usefulness' => 'required|integer|min:1|max:5',
            'post_demo_helpfulness' => 'required|integer|min:1|max:5',
            'post_topic_coverage' => 'required|integer|min:1|max:5',
            'post_hands_on_usefulness' => 'required|integer|min:1|max:5',
            'post_real_life_use' => 'required|integer|min:1|max:5',
            'post_cyber_use' => 'required|integer|min:1|max:5',
            'post_ai_use' => 'required|integer|min:1|max:5',
            'post_iot_use' => 'required|integer|min:1|max:5',
            'post_trainer_rating' => 'required|integer|min:1|max:5',
            'post_overall_satisfaction' => 'required|integer|min:1|max:5',
            'post_interest_increase' => 'required|integer|min:1|max:5',
            'post_innovation' => 'required|integer|min:1|max:5',
            'post_motivation_future' => 'required|integer|min:1|max:5',
        ]);


        // Get student's school ID automatically from StudentMst
        $student = StudentMst::where('stu_id', $request->stu_id)->first();
        $schoolId = $student->stu_scm_id ?? null;

        // Prepare the data for insert/update
        $data = $request->except(['_token']);
        $data['school_id'] = $schoolId;

        $data['post_interested_course'] = json_encode($request->post_interested_course);

        $data['school_id'] = $schoolId;

        // Save or update feedback record
        StudentFeedback::updateOrCreate(
            ['stu_id' => $request->stu_id],
            $data
        );

        // Re-evaluate training completion status
        $this->evaluateTrainingCompletion($schoolId);

        return redirect()
            ->route('student.feedback', ['school_id' => $request->school_id])
            ->with('success', 'Feedback saved successfully!');
    }

    private function evaluateTrainingCompletion(int $schoolId): void
    {
        $totalStudents = StudentMst::where('stu_scm_id', $schoolId)
            ->where('attendance', 1)
            ->count();

        if ($totalStudents < 120) {
            School::where('scm_id', $schoolId)
                ->update(['training_completed' => 0]);
            return;
        }

        //  All students STAR feedback entry check
        $studentsWithFeedback = StudentFeedback::where('school_id', $schoolId)
            ->distinct('stu_id')
            ->count('stu_id');

        $allStudentsFeedbackCompleted =
            ($studentsWithFeedback === $totalStudents);

        // Bulk feedback PDF uploaded
        $bulkFeedbackUploaded = TrainingUpload::where('school_id', $schoolId)
            ->where('file_type', 'written_feedback')
            ->exists();

        //  Required training files uploaded
        $requiredFiles = [
            'attendance_sheet',
            'training_photo',
            'training_video',
            'institute_feedback',
            'video_feedback',
            'training_completion_certificate'
        ];

        $uploadedFiles = TrainingUpload::where('school_id', $schoolId)
            ->whereIn('file_type', $requiredFiles)
            ->pluck('file_type')
            ->unique()
            ->toArray();

        $allTrainingFilesUploaded =
            empty(array_diff($requiredFiles, $uploadedFiles));

        // Default → NOT completed
        School::where('scm_id', $schoolId)
            ->update(['training_completed' => 0]);

        // FINAL DECISION
        if (
            $allStudentsFeedbackCompleted &&
            $bulkFeedbackUploaded &&
            $allTrainingFilesUploaded
        ) {
            School::where('scm_id', $schoolId)
                ->update(['training_completed' => 1]);
        }
    }
    public function index(Request $request)
    {
        $districtId = $request->get('district_id');
        $schoolId = $request->get('school_id');

        if ($schoolId) {
            $totalStudents = StudentMst::where('stu_scm_id', $schoolId)->count();
        } elseif ($districtId) {
            $totalStudents = StudentMst::where('stu_distid', $districtId)->count();
        } else {
            $totalStudents = StudentMst::count(); // overall
        }

        $districts = District::select('DSM_DSCD', 'DSM_DSNM')
            ->orderBy('DSM_DSNM')
            ->get();

        // Fetch schools based on selected district
        $schools = collect();
        if ($districtId) {
            $schools = School::where('scm_dist_id', $districtId)
                ->select('scm_id', 'scm_name')
                ->orderBy('scm_name')
                ->get();
        }
        // Filter feedback by school (if selected)
        $feedbackQuery = StudentFeedback::query();
        if ($schoolId) {
            $feedbackQuery->where('school_id', $schoolId);
        } elseif ($districtId) {
            $feedbackQuery->whereHas('school', function ($query) use ($districtId) {
                $query->where('scm_dist_id', $districtId);
            });
        }

        $feedbacks = $feedbackQuery->get();

        // Calculate average ratings for numeric fields
        $numericFields = [
            'pre_know_tech',
            'pre_confidence',
            'pre_interest',
            'pre_usefulness',
            'post_knowledge_improve',
            'post_confidence_now',
            'post_engagement',
            'post_usefulness',
            'post_demo_helpfulness',
            'post_topic_coverage',
            'post_hands_on_usefulness',
            'post_real_life_use',
            'post_trainer_rating',
            'post_overall_satisfaction',
            'post_interest_increase',
            'post_motivation_future'
        ];

        $averages = [];
        $counts = [];
        $trendData = [];

        foreach ($numericFields as $field) {
            $values = $feedbacks->pluck($field)->filter();
            $averages[$field] = round($values->avg(), 2);
            $counts[$field] = $values->count();

            $trendData[$field] = StudentFeedback::selectRaw("DATE(created_at) as date, AVG($field) as avg_rating, COUNT(*) as responses")
                ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->map(fn($r) => [
                    'date' => $r->date,
                    'avg_rating' => round($r->avg_rating, 2),
                    'responses' => $r->responses,
                ]);
        }

        //  Comparison data for Pre vs Post chart (now after averages are available)
        $comparison = [
            'Technology Known' => [
                'pre' => $averages['pre_know_tech'] ?? 0,
                'post' => $averages['post_knowledge_improve'] ?? 0,
            ],
            'Confidence' => [
                'pre' => $averages['pre_confidence'] ?? 0,
                'post' => $averages['post_confidence_now'] ?? 0,
            ],
            'Interest' => [
                'pre' => $averages['pre_interest'] ?? 0,
                'post' => $averages['post_interest_increase'] ?? 0,
            ],
            'Usefulness' => [
                'pre' => $averages['pre_usefulness'] ?? 0,
                'post' => $averages['post_usefulness'] ?? 0,
            ],
        ];

        $totalFeedbacks = $feedbacks->count();

        return view('feedback.report', compact('counts', 'districts', 'schools', 'averages', 'totalFeedbacks', 'districtId', 'schoolId', 'comparison', 'totalStudents', 'trendData'));
    }

    public function getSchoolsByDistrict($districtId)
    {
        return School::where('scm_dist_id', $districtId)
            ->select('scm_id', 'scm_name')
            ->orderBy('scm_name')
            ->get();
    }
}
