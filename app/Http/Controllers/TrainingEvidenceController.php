<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\School;
use App\Models\StudentMst;
use App\Models\TrainingUpload;
use App\Models\User;
use App\Services\OneDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrainingEvidenceController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function trainingphotos()
    {

        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        //  Check if this user already uploaded training photos
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        $existingUpload = TrainingUpload::where('uploaded_by', $userId)
            ->where('file_type', 'training_photo')
            ->first();

        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('trainingphotos', compact('schools'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'training_photo' => 'required|array',
            'training_photo.*' => 'file|mimes:jpg,jpeg,png|max:10240',
            'description'     => 'nullable|string',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $fileTypeMap = config('filetypes');

        $fileNames = [];
        $filePaths = [];
        $fileUrls  = [];

        if ($request->hasFile('training_photo')) {
            foreach ($request->file('training_photo') as $file) {

                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "EmergingTech/{$districtName}/{$schoolName}/training_photo";

                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

                $fileNames[] = $file->getClientOriginalName();
                $filePaths[] = $result['path'];
                $fileUrls[]  = $result['url'] ?? null;
            }
            TrainingUpload::create([
                'school_id'      => $schoolId,
                'file_type'      => 'training_photo',
                'filetype_id'    => $fileTypeMap['training_photo'],
                'file_name'      => $fileNames,
                'onedrive_path'  => $filePaths,
                'onedrive_url'   => $fileUrls,
                'uploaded_by'    => $userId,
                'training_date'  => $request->training_date,
                'description'    => $request->description,
            ]);
        }

        return  redirect()->route('trainingphotos.list')->with('success', 'training photo uploaded successfully!');
    }

    public function trainingphotoslist()
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
                $dlcId = $user->assignUnder_id; // DLC user_id
                $subUsers = User::where('assignUnder_id', $dlcId)->pluck('id'); // other coordinators/trainers under same DLC
                $visibleUserIds = $visibleUserIds->merge([$dlcId])->merge($subUsers);
            }
            $uploads = TrainingUpload::whereIn('uploaded_by', $visibleUserIds)->get();
        }

        return view('trainingphotoslist', compact('uploads', 'schools'));
    }

    public function editTrainingPhoto($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        if ($upload->file_type !== 'training_photo') {
            abort(403, 'Invalid file type');
        }

        return response()->json($upload); // we’ll load it dynamically in modal via JS
    }

    public function updateTrainingPhoto(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $schoolId = $upload->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $existingNames = $upload->file_name;
        $existingPaths = $upload->onedrive_path;
        $existingUrls  = $upload->onedrive_url;

        // Remove selected files
        if ($request->has('remove_files')) {
            foreach ($request->remove_files as $remove) {
                $index = array_search($remove, $existingNames);
                if ($index !== false) {
                    $pathToDelete = $existingPaths[$index] ?? null;

                    // Delete from OneDrive
                    if (!empty($pathToDelete)) {
                        try {
                            $this->oneDrive->deleteFile($pathToDelete);
                        } catch (\Exception $e) {
                            Log::warning("Failed to delete file from OneDrive: " . $e->getMessage());
                        }
                    }

                    unset($existingNames[$index]);
                    unset($existingPaths[$index]);
                    unset($existingUrls[$index]);
                }
            }
            $existingNames = array_values($existingNames);
            $existingPaths = array_values($existingPaths);
            $existingUrls  = array_values($existingUrls);
        }

        // Upload new files
        if ($request->hasFile('new_training_photo')) {
            foreach ($request->file('new_training_photo') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "EmergingTech/{$districtName}/{$schoolName}/training_photo";
                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

                $existingNames[] = $file->getClientOriginalName();
                $existingPaths[] = $result['path'];
                $existingUrls[]  = $result['url'] ?? null;
            }
        }

        // Update record
        $upload->update([
            'file_name'     => array_values($existingNames),
            'onedrive_path' => array_values($existingPaths),
            'onedrive_url'  => array_values($existingUrls),
        ]);

        return back()->with('success', 'Training photos updated successfully!');
    }

    public function previewImage(Request $request)
    {
        $path = $request->query('path');

        $fileInfo = $this->oneDrive->getFileInfo($path);
        if (!isset($fileInfo['@microsoft.graph.downloadUrl'])) {
            return response('Download URL not found', 404);
        }

        $downloadUrl = $fileInfo['@microsoft.graph.downloadUrl'];

        return response()->stream(function () use ($downloadUrl) {
            $response = Http::get($downloadUrl);
            echo $response->body();
        }, 200, ['Content-Type' => 'image/']);
    }

    public function trainingvideos()
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
        return view('trainingvideos', compact('schools'));
    }

    public function uploadvideo(Request $request)
    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'training_video' => 'required|array',
            'training_video.*' => 'mimes:mp4,avi,mov,mkv|max:112640',
            'description'     => 'nullable|string',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $fileTypeMap = config('filetypes');

        $fileNames = [];
        $filePaths = [];
        $fileUrls  = [];

        foreach ($request->file('training_video') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder   = "EmergingTech/{$districtName}/{$schoolName}/training_video";

            // Upload to OneDrive
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            // Collect values
            $fileNames[] = $file->getClientOriginalName();
            $filePaths[] = $result['path'] ?? null;
            $fileUrls[]  = $result['url'] ?? null;
        }
        TrainingUpload::create([
            'school_id'      => $schoolId,
            'file_type'      => 'training_video',
            'filetype_id'    => $fileTypeMap['training_video'] ?? null,
            'file_name'      => $fileNames,
            'onedrive_path'  => $filePaths,
            'onedrive_url'   => $fileUrls,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
        ]);

        return redirect()
            ->route('trainingvideos.list')
            ->with('success', 'training video uploaded successfully!');
    }

    public function previewVideo(Request $request)
    {
        $path = $request->query('path');

        $fileInfo = $this->oneDrive->getFileInfo($path);
        if (!isset($fileInfo['@microsoft.graph.downloadUrl'])) {
            return response('Download URL not found', 404);
        }

        $downloadUrl = $fileInfo['@microsoft.graph.downloadUrl'];

        // Detect correct video MIME type
        $mimeType = $fileInfo['file']['mimeType'] ?? 'video/mp4';

        return response()->stream(function () use ($downloadUrl) {

            $stream = fopen($downloadUrl, 'r');

            while (!feof($stream)) {
                echo fread($stream, 1024 * 64); // 64KB chunks
                ob_flush();
                flush();
            }

            fclose($stream);
        }, 200, [
            'Content-Type'        => $mimeType,
            'Accept-Ranges'       => 'bytes',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }

    public function trainingvideoslist()
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
                $dlcId = $user->assignUnder_id; // DLC user_id
                $subUsers = User::where('assignUnder_id', $dlcId)->pluck('id'); // other coordinators/trainers under same DLC
                $visibleUserIds = $visibleUserIds->merge([$dlcId])->merge($subUsers);
            }
            $uploads = TrainingUpload::whereIn('uploaded_by', $visibleUserIds)->get();
        }

        return view('trainingvideoslist', compact('uploads', 'schools'));
    }

    public function editTrainingVideo($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        if ($upload->file_type !== 'training_video') {
            abort(403, 'Invalid file type');
        }

        return response()->json($upload); // we’ll load it dynamically in modal via JS
    }

    public function updateTrainingVideo(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $schoolId = $upload->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $existingNames = $upload->file_name;
        $existingPaths = $upload->onedrive_path;
        $existingUrls  = $upload->onedrive_url;

        if ($request->has('remove_files')) {
            foreach ($request->remove_files as $remove) {
                $index = array_search($remove, $existingNames);
                if ($index !== false) {
                    $pathToDelete = $existingPaths[$index] ?? null;

                    if (!empty($pathToDelete)) {
                        try {
                            $this->oneDrive->deleteFile($pathToDelete);
                        } catch (\Exception $e) {
                            Log::warning("Failed to delete file from OneDrive: " . $e->getMessage());
                        }
                    }

                    unset($existingNames[$index]);
                    unset($existingPaths[$index]);
                    unset($existingUrls[$index]);
                }
            }
            $existingNames = array_values($existingNames);
            $existingPaths = array_values($existingPaths);
            $existingUrls  = array_values($existingUrls);
        }

        // Upload new files
        if ($request->hasFile('new_training_video')) {
            foreach ($request->file('new_training_video') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "EmergingTech/{$districtName}/{$schoolName}/training_video";
                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

                $existingNames[] = $file->getClientOriginalName();
                $existingPaths[] = $result['path'];
                $existingUrls[]  = $result['url'] ?? null;
            }
        }

        $upload->update([
            'file_name'     => array_values($existingNames),
            'onedrive_path' => array_values($existingPaths),
            'onedrive_url'  => array_values($existingUrls),
        ]);

        return back()->with('success', 'Training Video updated successfully!');
    }

    public function trainingcompcertificate()
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

        // Check if user has uploaded all required files
        $requiredFiles = [
            'attendance_sheet'  => 'Attendance Sheet',
            'training_photo'    => 'Training Photo',
            'training_video'    => 'Training Video',
            'written_feedback'  => 'Student Feedback',
            'institute_feedback' => 'Institute Feedback',
            'video_feedback'    => 'Video Feedback',
        ];

        $selectedSchoolId = request()->get('school_id');

        $uploadedFiles = [];
        if ($selectedSchoolId) {
            $uploadedFiles = TrainingUpload::where('uploaded_by', $userId)
                ->where('school_id', $selectedSchoolId)
                ->pluck('file_type')
                ->toArray();
        }

        // Calculate which of the required ones are completed
        $completedFiles = array_intersect(array_keys($requiredFiles), $uploadedFiles);

        $progress = count($completedFiles) / count($requiredFiles) * 100;
        // Determine if all files are uploaded
        $canUploadCertificate = $selectedSchoolId
            && count(array_unique($completedFiles)) === count($requiredFiles);

        $trainingCompleted = false;

        if ($selectedSchoolId) {
            $trainingCompleted = School::where('scm_id', $selectedSchoolId)
                ->where('training_completed', 1)
                ->exists();
        }

        return view('trainingcompcertificate', compact('schools', 'requiredFiles', 'uploadedFiles', 'progress', 'canUploadCertificate', 'selectedSchoolId', 'trainingCompleted'));
    }

    public function uploadcertificate(Request $request)
    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'training_completion_certificate.*' => 'required|mimes:pdf,jpg,jpeg,png|max:10240',
            'declaration' => 'accepted',
            'training_completed' => 'accepted',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $fileTypeMap = config('filetypes');

        $file = $request->file('training_completion_certificate');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder   = "EmergingTech/{$districtName}/{$schoolName}/training_completion_certificate";

        // Upload to OneDrive
        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

        TrainingUpload::create([
            'school_id'      => $schoolId,
            'coordinator_id' => $userId,
            'file_type'      => 'training_completion_certificate',
            'filetype_id'    => $fileTypeMap['training_completion_certificate'] ?? null,
            'file_name'      => $file->getClientOriginalName(),
            'onedrive_path'  => $result['path'] ?? null,
            'onedrive_url'   => $result['url'] ?? null,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
        ]);
        
        //  Re-check training completion after certificate upload
        $this->evaluateTrainingCompletion($schoolId);

        return redirect()
            ->route('trainingcompcertificate.list')
            ->with('success', 'training completion certificate uploaded successfully!');
        // return back()->with('success', 'training completion certificate uploaded successfully!');
    }

    private function evaluateTrainingCompletion(int $schoolId): void
    {
        // Required training files
        $requiredFiles = [
            'attendance_sheet',
            'training_photo',
            'training_video',
            'written_feedback',
            'institute_feedback',
            'video_feedback',
            'training_completion_certificate'
        ];

        $uploadedFiles = TrainingUpload::where('school_id', $schoolId)
            ->whereIn('file_type', $requiredFiles)
            ->pluck('file_type')
            ->unique()
            ->toArray();

        $allTrainingFilesUploaded = empty(array_diff($requiredFiles, $uploadedFiles));

        // Student attendance count
        $totalStudents = StudentMst::where('stu_scm_id', $schoolId)
            ->where('attendance', 1)
            ->count();

        $studentsWithFeedback = StudentMst::where('stu_scm_id', $schoolId)
            ->where('attendance', 1)
            ->whereNotNull('feedback_file_url')
            ->count();

        $meetsStudentRule =
            ($totalStudents >= 120 && $studentsWithFeedback === $totalStudents);

        // if ($allTrainingFilesUploaded && $meetsStudentRule) {
        if ($allTrainingFilesUploaded) {
            School::where('scm_id', $schoolId)
                ->update(['training_completed' => 1]);
        } else {
            School::where('scm_id', $schoolId)
                ->update(['training_completed' => 0]);
        }
    }
    public function trainingcompcertificatelist()
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

        return view('trainingcompcertificatelist', compact('uploads', 'schools'));
    }
    public function editTrainingCompletionCertificate($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        if ($upload->file_type !== 'training_completion_certificate') {
            abort(403, 'Invalid file type');
        }

        return response()->json($upload); // we’ll load it dynamically in modal via JS
    }
    public function updateTrainingCompletionCertificate(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $schoolId = $upload->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        // Upload new file
        if ($request->hasFile('new_training_completion_certificate')) {
            $file = $request->file('new_training_completion_certificate');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "EmergingTech/{$districtName}/{$schoolName}/training_completion_certificate";
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            // Update record
            $upload->update([
                'file_name'     => $file->getClientOriginalName(),
                'onedrive_path' => $result['path'],
                'onedrive_url'  => $result['url'] ?? null,
            ]);
        }

        return back()->with('success', 'Training Completion Certificate updated successfully!');
    }
    public function viewUploadedCertificates(Request $request)
    {
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM')->get();

        $districtId = $request->district_id;
        $schoolId   = $request->school_id;

        // Load schools for selected district
        if ($districtId) {
            $schools = School::where('scm_dist_id', $districtId)
                ->select('scm_id', 'scm_name', 'scm_dist_id')
                ->orderBy('scm_name')
                ->get();
        } else {
            $schools = collect();
        }

        // Fetch uploaded certificates if school is selected
        $certificates = collect();
        if ($districtId) {
            $query = TrainingUpload::query()
                ->where('file_type', 'training_completion_certificate')
                ->with(['user', 'school', 'school.district']);

            if ($schoolId) {
                //  specific school
                $query->where('school_id', $schoolId);
            } else {
                //  all schools in that district
                $schoolIds = $schools->pluck('scm_id');
                $query->whereIn('school_id', $schoolIds);
            }

            $certificates = $query->get();
        }

        return view('viewcertificates', compact('districts', 'districtId', 'schools', 'schoolId', 'certificates'));
    }
    public function previewFile(Request $request)
    {
        $path = $request->query('path');

        $downloadUrl = Cache::remember("onedrive_download_" . md5($path), 300, function () use ($path) {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            return $fileInfo['@microsoft.graph.downloadUrl'] ?? null;
        });

        if (!$downloadUrl) {
            return response('Download URL not found', 404);
        }
        // Fetch the file content
        $response = Http::get($downloadUrl);
        $contentType = $response->header('Content-Type', 'application/octet-stream');

        // Stream based on type
        if (str_contains($contentType, 'pdf')) {
            return response($response->body(), 200)->header('Content-Type', 'application/pdf');
        } elseif (str_contains($contentType, 'image')) {
            return response($response->body(), 200)->header('Content-Type', $contentType);
        } else {
            return response('Unsupported file type', 415);
        }
    }
}
