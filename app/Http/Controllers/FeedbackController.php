<?php

namespace App\Http\Controllers;

use App\Models\InstituteFeedback;
use App\Models\School;
use App\Models\TrainingUpload;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }
    public function writtenfeedback()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('writtenfeedback', compact('schools'));
    }
    public function uploadwrittenfeedback(Request $request)

    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'written_feedback' => 'required|mimes:pdf|max:51200',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $fileTypeMap = config('filetypes');

        $file = $request->file('written_feedback');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder   = "EmergingTech/{$districtName}/{$schoolName}/Student_feedback";

        // Upload to OneDrive
        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);


        TrainingUpload::create([
            'school_id'      => $schoolId,
            'file_type'      => 'written_feedback',
            'filetype_id'    => $fileTypeMap['written_feedback'] ?? null,
            'file_name'      => $file->getClientOriginalName(),
            'onedrive_path'  => $result['path'] ?? null,
            'onedrive_url'   => $result['url'] ?? null,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
        ]);

        return back()->with('success', 'Feedback uploaded successfully!');
    }

    public function writtenfeedbacklist()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $schools = School::select('scm_id', 'scm_name', 'scm_udise_code')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();

        if ($roleId == 1 || $roleId == 2) {
            $uploads = TrainingUpload::latest()->get();
        } else {
            $visibleUserIds = collect([$userId]); // Always include self

            if ($roleId == 3) {
                // DLC: see uploads by themselves + coordinators + trainers under them
                $subUsers = User::where('assignUnder_id', $userId)->pluck('id');
                $visibleUserIds = $visibleUserIds->merge($subUsers);
            } elseif ($roleId == 6 || $roleId == 5) {

                // Coordinator: see own uploads + DLC + trainers under same DLC
                $dlcId = $user->assignUnder_id; // DLC user_id
                $subUsers = User::where('assignUnder_id', $dlcId)->pluck('id'); // other coordinators/trainers under same DLC
                $visibleUserIds = $visibleUserIds->merge([$dlcId])->merge($subUsers);
            }
            $uploads = TrainingUpload::whereIn('uploaded_by', $visibleUserIds)->get();
        }

        return view('writtenfeedbacklist', compact('uploads', 'schools'));
    }
    public function editwrittenFeedback($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        if ($upload->file_type !== 'written_feedback') {
            abort(403, 'Invalid file type');
        }

        return response()->json([
            'upload_id'     => $upload->upload_id,
            'file_name'     => $upload->file_name,
            'onedrive_path' => $upload->onedrive_path,
            'onedrive_url'  => $upload->onedrive_url,
            'file_type'     => $upload->file_type,
        ]);
    }

    public function updatewrittenfeedback(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $request->validate([
            'new_written_feedback' => 'nullable|mimes:pdf,jpeg,png,jpg|max:51200',
        ]);

        if ($request->hasFile('new_written_feedback')) {

            $existingPath = $upload->onedrive_path;
            if (is_array($existingPath)) {
                $existingPath = $existingPath[0] ?? null;
            }

            if (!empty($existingPath)) {
                try {
                    $this->oneDrive->deleteFile($existingPath);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old written feedback from OneDrive: " . $e->getMessage());
                }
            }

            $file = $request->file('new_written_feedback');
            $filename = time() . '_' . $file->getClientOriginalName();
            $schoolId = $upload->school_id;
            $school = School::find($schoolId);
            $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
            $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);
            $folder = "EmergingTech/{$districtName}/{$schoolName}/Student_feedback";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            $upload->file_name = $file->getClientOriginalName();
            $upload->onedrive_path = $result['path'];
            $upload->onedrive_url = $result['url'] ?? null;
            $upload->save();
        }

        return back()->with('success', 'Student Feedback updated successfully!');
    }

    public function instituteFeedback()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        // Check if user already uploaded Institute Feedback
        $uploads = TrainingUpload::where('uploaded_by', $userId)
            ->whereIn('file_type', ['institute_feedback'])
            ->get();

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        return view('feedback.institutefeedback', compact('schools', 'uploads'));
    }
    public function instituteFeedbackStore(Request $request)
    {
        $request->validate([
            'school_id'          => 'required|integer',
            'training_date'      => 'required|date',
            'institute_feedback' => 'required|mimes:pdf,jpeg,png,jpg|max:10240',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $fileTypeMap = config('filetypes');

        $file = $request->file('institute_feedback');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder   = "EmergingTech/{$districtName}/{$schoolName}/institute_feedback";

        // Upload to OneDrive
        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

        TrainingUpload::create([
            'school_id'      => $schoolId,
            'file_type'      => 'institute_feedback',
            'filetype_id'    => $fileTypeMap['institute_feedback'] ?? null,
            'file_name'      => $file->getClientOriginalName(),
            'onedrive_path'  => $result['path'] ?? null,
            'onedrive_url'   => $result['url'] ?? null,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
        ]);

        return back()->with('success', 'Institute Feedback uploaded successfully!');
    }
    public function instituteFeedbackList()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $schools = School::select('scm_id', 'scm_name', 'scm_udise_code')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();

        if ($roleId == 1 || $roleId == 2) {
            $uploads = TrainingUpload::latest()->get();
        } else {
            $visibleUserIds = collect([$userId]); // Always include self

            if ($roleId == 3) {
                // DLC: see uploads by themselves + coordinators + trainers under them
                $subUsers = User::where('assignUnder_id', $userId)->pluck('id');
                $visibleUserIds = $visibleUserIds->merge($subUsers);
            } elseif ($roleId == 6 || $roleId == 5) {

                // Coordinator: see own uploads + DLC + trainers under same DLC
                $dlcId = $user->assignUnder_id; // DLC user_id
                $subUsers = User::where('assignUnder_id', $dlcId)->pluck('id'); // other coordinators/trainers under same DLC
                $visibleUserIds = $visibleUserIds->merge([$dlcId])->merge($subUsers);
            }
            $uploads = TrainingUpload::whereIn('uploaded_by', $visibleUserIds)->get();
        }

        return view('feedback.institutefeedbacklist', compact('uploads', 'schools'));
    }

    public function instituteFeedbackEdit($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        if ($upload->file_type !== 'institute_feedback') {
            abort(403, 'Invalid file type');
        }

        return response()->json([
            'upload_id'     => $upload->upload_id,
            'file_name'     => $upload->file_name,
            'onedrive_path' => $upload->onedrive_path,
            'onedrive_url'  => $upload->onedrive_url,
            'file_type'     => $upload->file_type,
        ]);
    }

    public function instituteFeedbackUpdate(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $request->validate([
            'new_institute_feedback' => 'nullable|mimes:pdf,jpeg,png,jpg|max:10240',
        ]);

        if ($request->hasFile('new_institute_feedback')) {

            $existingPath = $upload->onedrive_path;
            if (is_array($existingPath)) {
                $existingPath = $existingPath[0] ?? null;
            }

            if (!empty($existingPath)) {
                try {
                    $this->oneDrive->deleteFile($existingPath);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old institute feedback from OneDrive: " . $e->getMessage());
                }
            }

            $file = $request->file('new_institute_feedback');
            $filename = time() . '_' . $file->getClientOriginalName();
            $schoolId = $upload->school_id;
            $school = School::find($schoolId);
            $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
            $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);
            $folder = "EmergingTech/{$districtName}/{$schoolName}/institute_feedback";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            $upload->file_name = $file->getClientOriginalName();
            $upload->onedrive_path = $result['path'];
            $upload->onedrive_url = $result['url'] ?? null;
            $upload->save();
        }

        return back()->with('success', 'Institute Feedback updated successfully!');
    }
    public function instituteFeedbackEntry(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        $selectedSchoolId = $request->school_id;
        $existingFeedback = null;
        $trainingDate = null;

        if ($selectedSchoolId) {
            $existingFeedback = InstituteFeedback::where('school_id', $selectedSchoolId)->first();
            $trainingDate = $schools->firstWhere('scm_id', $selectedSchoolId)?->training_date;
        }

        return view('feedback.institutefeedbackentry', compact('schools', 'existingFeedback', 'trainingDate', 'selectedSchoolId'));
    }

    public function instituteFeedbackEntryStore(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:school_mst,scm_id',
            'training_date' => 'required|date',

            'planning_coordination' => 'required|integer|min:1|max:5',
            'timeliness_discipline' => 'required|integer|min:1|max:5',
            'trainer_quality' => 'required|integer|min:1|max:5',
            'student_engagement' => 'required|integer|min:1|max:5',
            'clarity_explanation' => 'required|integer|min:1|max:5',
            'iot_robotics_usefulness' => 'required|integer|min:1|max:5',
            'ai_relevance' => 'required|integer|min:1|max:5',
            'cyber_awareness_need' => 'required|integer|min:1|max:5',
            'equipment_quality' => 'required|integer|min:1|max:5',
            'overall_impact' => 'required|integer|min:1|max:5',

            'awareness_increased' => 'required|in:0,1',
            'student_enthusiasm' => 'required',
            'program_beneficial' => 'required',
            'future_program_interest' => 'required|array',
        ]);

        InstituteFeedback::updateOrCreate([
            'school_id' => $request->school_id],
        [
                'training_date' => $request->training_date,

                'planning_coordination' => $request->planning_coordination,
                'timeliness_discipline' => $request->timeliness_discipline,
                'trainer_quality' => $request->trainer_quality,
                'student_engagement' => $request->student_engagement,
                'clarity_explanation' => $request->clarity_explanation,
                'iot_robotics_usefulness' => $request->iot_robotics_usefulness,
                'ai_relevance' => $request->ai_relevance,
                'cyber_awareness_need' => $request->cyber_awareness_need,
                'equipment_quality' => $request->equipment_quality,
                'overall_impact' => $request->overall_impact,

                'awareness_increased' => $request->awareness_increased,
                'student_enthusiasm' => $request->student_enthusiasm,
                'program_beneficial' => $request->program_beneficial,
                'future_program_interest' => json_encode($request->future_program_interest ?? []),

                'submitted_by' => Auth::id(),
            ]
        );

        return redirect()->route('institute.feedback.entry', ['school_id' => $request->school_id])
            ->with('success', 'feedback saved successfully!');
    }

    public function videofeedback()
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

        return view('uploadfeedback', compact('schools'));
    }

    public function uploadvideofeedback(Request $request)
    {
        $request->validate([
            'school_id'      => 'required|integer',
            'training_date'  => 'required|date',
            'designation'    => 'required|string',
            'video_feedback' => 'required|mimes:mp4,mov,avi,wmv|max:112640',
            'description'    => 'nullable|string',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);


        $fileTypeMap = config('filetypes');

        $file = $request->file('video_feedback');
        $filename = time() . '_' . $file->getClientOriginalName();
        $designation = $request->designation;
        $folder   = "EmergingTech/{$districtName}/{$schoolName}/video_feedback/designation_{$designation}";

        // Upload to OneDrive
        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

        TrainingUpload::create([
            'school_id'      => $schoolId,
            'file_type'      => 'video_feedback',
            'filetype_id'    => $fileTypeMap['video_feedback'] ?? null,
            'file_name'      => $file->getClientOriginalName(),
            'onedrive_path'  => $result['path'] ?? null,
            'onedrive_url'   => $result['url'] ?? null,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
            'designation'    => $request->designation,
        ]);

        return redirect()->route('videofeedback.list')->with('success', 'Feedback video uploaded successfully!');
    }

    public function videofeedbacklist()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id; // 3 = DLC, 6 = Coordinator, 5 = Trainer

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $schools = School::select('scm_id', 'scm_name', 'scm_udise_code')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();

        if ($roleId == 1 || $roleId == 2) {
            $uploads = TrainingUpload::latest()->get();
        } else {
            $visibleUserIds = collect([$userId]); // Always include self

            if ($roleId == 3) {
                // DLC: see uploads by themselves + coordinators + trainers under them
                $subUsers = User::where('assignUnder_id', $userId)->pluck('id');
                $visibleUserIds = $visibleUserIds->merge($subUsers);
            } elseif ($roleId == 6 || $roleId == 5) {

                // Coordinator: see own uploads + DLC + trainers under same DLC
                $dlcId = $user->assignUnder_id;
                $subUsers = User::where('assignUnder_id', $dlcId)->pluck('id'); // other coordinators/trainers under same DLC
                $visibleUserIds = $visibleUserIds->merge([$dlcId])->merge($subUsers);
            }
            $uploads = TrainingUpload::whereIn('uploaded_by', $visibleUserIds)->get();
        }

        return view('videofeedbacklist', compact('uploads', 'schools'));
    }
    public function editFeedbackVideo($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        if ($upload->file_type !== 'video_feedback') {
            abort(403, 'Invalid file type');
        }

        // we take the first element (assuming only one exists).
        $fileName = !empty($upload->file_name) ? $upload->file_name[0] : null;
        $path     = !empty($upload->onedrive_path) ? $upload->onedrive_path[0] : null;
        $url      = !empty($upload->onedrive_url) ? $upload->onedrive_url[0] : null;

        return response()->json([
            'upload_id'     => $upload->upload_id,
            'file_name'     => $fileName,
            'onedrive_path' => $path,
            'onedrive_url'  => $url,
            'file_type'     => $upload->file_type,
            'designation'   => $upload->designation,
        ]);
    }

    public function updatevideofeedback(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $oldDesignation = $upload->designation;
        $newDesignation = $request->designation;
        
        $request->validate([
            'new_feedback_video' => 'nullable|mimes:mp4,mov,avi,wmv|max:112640',
        ]);

        $schoolId = $upload->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);
        
        $oldfolder = "EmergingTech/{$districtName}/{$schoolName}/video_feedback/designation_{$oldDesignation}";
        $newfolder = "designation_{$newDesignation}";
        
        if ($oldDesignation !== $newDesignation) {
            try {
                $this->oneDrive->renameFolder($oldfolder, $newfolder);

                // Update ONE saved path
                $existingPath = is_array($upload->onedrive_path)
                    ? ($upload->onedrive_path[0] ?? null)
                    : $upload->onedrive_path;

                // Replace old folder name with new one in path
                if ($existingPath) {
                    $upload->onedrive_path = [
                        str_replace(
                            "designation_{$oldDesignation}",
                            "designation_{$newDesignation}",
                            $existingPath
                        )
                    ];
                }

            } catch (\Exception $e) {
                Log::warning("Failed to rename feedback folder: " . $e->getMessage());
            }
        }

        if ($request->hasFile('new_feedback_video')) {

            $existingPath = $upload->onedrive_path;
            if (is_array($existingPath)) {
                $existingPath = $existingPath[0] ?? null;
            }

            if (!empty($existingPath)) {
                try {
                    $this->oneDrive->deleteFile($existingPath);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old feedback video from OneDrive: " . $e->getMessage());
                }
            }

            $file = $request->file('new_feedback_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "EmergingTech/{$districtName}/{$schoolName}/video_feedback/{$newfolder}";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            $upload->file_name = [$file->getClientOriginalName()];
            $upload->onedrive_path = [$result['path']];
            $upload->onedrive_url = [$result['url'] ?? null];
        }
        $upload->designation = $newDesignation;
        $upload->save();

        return back()->with('success', 'Feedback Video updated successfully!');
    }

    public function previewVideo(Request $request)
    {
        $path = $request->query('path');

        // Cache download URL for performance
        $downloadUrl = Cache::remember("onedrive_video_$path", 300, function () use ($path) {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            return $fileInfo['@microsoft.graph.downloadUrl'] ?? null;
        });

        if (!$downloadUrl) {
            return response('Video not found', 404);
        }

        // Stream video directly from OneDrive
        $response = Http::get($downloadUrl);

        return response($response->body(), 200)
            ->header('Content-Type', 'video/mp4'); // use video/mp4
    }
}
