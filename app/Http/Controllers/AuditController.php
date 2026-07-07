<?php

namespace App\Http\Controllers;
use App\Models\InstituteFeedback;
use App\Models\School;
use App\Models\StudentFeedback;
use App\Models\StudentMst;
use App\Models\TrainingUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function trainingAudit(Request $request)
    {
        $districts = School::select('scm_dist')->distinct()->orderBy('scm_dist')->get();

        $selectedDistrict = $request->district;
        $selectedSchoolId = $request->school_id;

        $schools = collect();
        $school = null;
        $stats = null;

        if ($selectedDistrict) {
            $schools = School::where('scm_dist', $selectedDistrict)
                ->orderBy('scm_name')
                ->get();
        }

        if ($selectedSchoolId) {
            $school = School::findOrFail($selectedSchoolId);

            $uploaded = TrainingUpload::where('school_id', $selectedSchoolId)
                ->pluck('file_type')
                ->unique()
                ->toArray();

            $totalStudents = StudentMst::where('stu_scm_id', $selectedSchoolId)
                ->where('attendance', 1)
                ->count();

            $feedbackUploaded = StudentMst::where('stu_scm_id', $selectedSchoolId)
                ->where('attendance', 1)
                ->whereNotNull('feedback_file_url')
                ->count();

            $stats = [
                'attendance_sheet' => in_array('attendance_sheet', $uploaded),
                'training_video'   => in_array('training_video', $uploaded),
                'video_feedback'   => in_array('video_feedback', $uploaded),
                'certificate'      => in_array('training_completion_certificate', $uploaded),
                'feedback_count'   => "{$feedbackUploaded} / {$totalStudents}",
                'training_completed' => $school->training_completed,
                'audit_status'     => $school->audit_status,
            ];
        }

        return view('audit.trainingaudit', compact(
            'districts',
            'schools',
            'school',
            'stats',
            'selectedDistrict',
            'selectedSchoolId'
        ));
    }

    public function auditAction(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Accounts') {
            abort(403, 'Unauthorized action');
        }

        $request->validate([
            'school_id' => 'required|exists:school_mst,scm_id',
            'action'    => 'required|in:approve,reject,revert',
            'comment'   => 'required|string|max:500',
        ]);

        //  CHECK STUDENT FEEDBACK COUNT
        $totalStudents = StudentMst::where('stu_scm_id', $request->school_id)
            ->where('attendance', 1)
            ->count();

        // $studentsWithFeedback = StudentMst::where('stu_scm_id', $request->school_id)
        //     ->where('attendance', 1)
        //     ->whereNotNull('feedback_file_url')
        //     ->count();

        $studentRatingCount = StudentFeedback::where('school_id', $request->school_id)
            ->whereIn('stu_id', function ($q) use ($request) {
                $q->select('stu_id')
                ->from('student_mst')
                ->where('stu_scm_id', $request->school_id)
                ->where('attendance', 1);
            })->count();

        $instituteRatingCount = InstituteFeedback::where('school_id', $request->school_id)->count();

        $hasCertificate = TrainingUpload::where('school_id', $request->school_id)
            ->where('file_type', 'training_completion_certificate')
            ->exists();

        //  BLOCK APPROVAL IF FEEDBACK < 120
        if ($request->action === 'approve') {
            if (!$hasCertificate) {
                return back()->withErrors([
                    'approve' => 'Training completion certificate is required.',
                ]);
            }
            if ($studentRatingCount < 120) {
                return back()->withErrors([
                    'approve' => 'Minimum 120 student feedback ratings are required.',
                ]);
            }

            if ($studentRatingCount !== $totalStudents) {
                return back()->withErrors([
                    'approve' => 'Student feedback rating must be submitted for all attended students.',
                ]);
            }
            if ($instituteRatingCount < 1) {
                return back()->withErrors([
                    'approve' => 'Institute feedback rating entry is required.',
                ]);
            }
        }

        $statusMap = [
            'approve' => 'approved',
            'reject'  => 'rejected',
            'revert'  => 'reverted',
        ];

        // Decide training_completed based on audit action
        $trainingCompleted = match ($request->action) {
            'approve' => 1,
            default   => 0,
        };

        School::where('scm_id', $request->school_id)->update([
            'audit_status'      => $statusMap[$request->action],
            'audit_comment'     => $request->comment,
            'audit_by'        => $user->id,
            'audit_at'        => now(),
            'training_completed'=> $trainingCompleted,
        ]);

        return redirect()
            ->route('audit.list')
            ->with('success', 'Audit action recorded successfully');
    }


    public function auditList(Request $request)
    {
        $query = School::forBatch()
            ->whereNotNull('training_completed');

        if ($request->filled('status')) {
            $query->where('audit_status', $request->status);
        }

        if ($request->filled('district')) {
            $query->where('scm_dist', $request->district);
        }

        $schools = $query
            ->orderBy('scm_dist', 'asc')
            ->get();

        $districts = School::select('scm_dist')->distinct()->orderBy('scm_dist')->get();

        return view('audit.training_audit_list', compact('schools', 'districts'));
    }

    public function show($schoolId)
    {
        $school = School::with([
            'trainingUploads',
        ])->findOrFail($schoolId);

        $totalStudents = StudentMst::where('stu_scm_id', $schoolId)
            ->where('attendance', 1)
            ->count();

        // $studentsWithFeedback = StudentMst::where('stu_scm_id', $schoolId)
        //     ->where('attendance', 1)
        //     ->whereNotNull('feedback_file_url')
        //     ->count();

        $instituteFeedbackEntrys = InstituteFeedback::where('school_id', $schoolId)->get();

        $studentsWithFeedbackEntrys =StudentFeedback::where('school_id', $schoolId)
            ->whereIn('stu_id', function ($q) use ($schoolId) {
                $q->select('stu_id')
                    ->from('student_mst')
                    ->where('stu_scm_id', $schoolId)
                    ->where('attendance', 1);
            })->get();

        return view('audit.show', compact(
            'school',
            'totalStudents',
            'studentsWithFeedbackEntrys',
            'instituteFeedbackEntrys'
        ));
    }


}
