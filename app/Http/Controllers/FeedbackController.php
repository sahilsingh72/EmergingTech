<?php

namespace App\Http\Controllers;

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
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('writtenfeedback', compact('schools'));
    }
    public function uploadwrittenfeedback(Request $request)

    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'written_feedback' => 'required|mimes:pdf|max:10240',
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;

        $fileTypeMap = config('filetypes');

        $file = $request->file('written_feedback');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder   = "School_{$schoolId}/User_{$userId}/written_feedback";

        // Upload to OneDrive
        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);


        TrainingUpload::create([
            'school_id'      => $schoolId,
            'coordinator_id' => $userId,
            'file_type'      => 'written_feedback',
            'filetype_id'    => $fileTypeMap['written_feedback'] ?? null,
            'file_name'      => $file->getClientOriginalName(),
            'onedrive_path'  => $result['path'] ?? null,
            'onedrive_url'   => $result['url'] ?? null,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
        ]);

        return back()->with('success', 'Feedback uploaded successfully!');
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
            'video_feedback' => 'required|mimes:mp4,mov,avi,wmv|max:102400',
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

        return redirect()->route('trainingphotos.list')->with('success', 'Feedback video uploaded successfully!');
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
            'new_feedback_video' => 'nullable|mimes:mp4,mov,avi,wmv|max:102400',
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
