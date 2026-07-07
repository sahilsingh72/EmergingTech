<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\School;
use App\Models\StudentMst;
use Illuminate\Http\Request;
use App\Models\TrainingUpload;
use App\Models\User;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function studentAttendance(Request $request)
    {

        $userId   = Auth::id();
        $roleId = Auth::user()->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        $schoolId = $request->input('school_id');

        $studentsQuery = StudentMst::orderBy('attendance', 'desc')
            ->orderBy('stu_name', 'asc')
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
            $schools = School::forBatch()->select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::forBatch()->select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        return view('attendancesheet', compact('students', 'schools', 'schoolId' ,'districts'));
    }

    public function getSchoolsByDistrict(Request $request)
    {
        $districtId = $request->district_id;

        $schools = School::forBatch()->where('scm_dist_id', $districtId)
            ->orderBy('scm_name', 'asc')
            ->get(['scm_id', 'scm_name', 'scm_udise_code']);

        return response()->json($schools);
    }

    public function saveAll(Request $request)
    {
        $presentCount = 0;

        foreach ($request->attendance as $item) {
            if ($item['attendance'] == 1) {
                $presentCount++;
            }
        }

        //  BLOCK SAVE if present < 120
        if ($presentCount < 120) {
            return response()->json([
                'error' => "More than 120 students must be marked Present. You marked $presentCount."
            ], 422);
        }

        foreach ($request->attendance as $item) {
            StudentMst::where('stu_id', $item['student_id'])
                ->update(['attendance' => $item['attendance']]);
        }

        return response()->json(['message' => 'Saved']);
    }

    public function attendance()
    {

        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        // Check if user already uploaded attendance & trainer image
        $uploads = TrainingUpload::where('uploaded_by', $userId)
            ->whereIn('file_type', ['attendance_sheet', 'trainer_photo'])
            ->get();

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::forBatch()->select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::forBatch()->select('scm_id', 'scm_name', 'scm_udise_code', 'training_date')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('studentattendance', compact('schools'));
    }
    public function upload(Request $request)
    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'attendance_files' => 'required|array',
            'attendance_files.*' => 'file|mimetypes:image/*,application/pdf|max:10240',
            'trainer_image'    => 'required|file|mimetypes:image/*|max:10240'
        ]);

        $userId   = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $attendanceFileNames = [];
        $attendancePaths     = [];
        $attendanceUrls      = [];

        // --- Upload Attendance Files (each file = one row) ---
        if ($request->hasFile('attendance_files')) {
            foreach ($request->file('attendance_files') as $file) {

                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "EmergingTech/{$districtName}/{$schoolName}/attendance_sheet";

                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);
                $fileTypeMap = config('filetypes');
                $attendanceFileNames[] = $file->getClientOriginalName();
                $attendancePaths[]     = $result['path'];
                $attendanceUrls[]      = $result['url'] ?? null;
            }
            TrainingUpload::create([
                'school_id'      => $schoolId,
                'file_type'      => 'attendance_sheet',
                'filetype_id'    => $fileTypeMap['attendance_sheet'],
                'file_name'      => $attendanceFileNames,
                'onedrive_path'  => $attendancePaths,
                'onedrive_url'   => $attendanceUrls,
                'uploaded_by'    => $userId,
                'training_date'  => $request->training_date,
                'description'    => $request->description,
            ]);
        }


        // --- Upload Trainer Image (single row) ---
        if ($request->hasFile('trainer_image')) {
            $file = $request->file('trainer_image');
            $filename = time() . '_' . $file->getClientOriginalName();

            $folder = "EmergingTech/{$districtName}/{$schoolName}/trainer_photo";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);
            $fileTypeMap = config('filetypes');
            TrainingUpload::create([
                'school_id'      => $schoolId,
                'file_type'      => 'trainer_photo',
                'filetype_id'    => $fileTypeMap['trainer_photo'],
                'file_name'      => [$file->getClientOriginalName()],
                'onedrive_path'  => [$result['path']],
                'onedrive_url'   => [$result['url'] ?? null],
                'uploaded_by'    => $userId,
                'training_date'  => $request->training_date,
                'description'    => $request->description,
            ]);
        }


        return  redirect()->route('attendance.list')->with('success', 'Attendance and trainer image uploaded successfully!');
    }
    public function attendanceList(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;
        $schoolId = $request->school_id;
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        $schoolsQuery = School::forBatch()
            ->select('scm_id', 'scm_name', 'scm_udise_code');

        if (!in_array($roleId, [1, 2, 8])) {
            $schoolsQuery->where('scm_dist_id', $districtID[0]->district_id);
        }

        $schools = $schoolsQuery->orderBy('scm_name', 'asc')->get();
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();

        $uploadsQuery = TrainingUpload::with('school')
            ->where('file_type', 'attendance_sheet')
            ->whereHas('school', function ($query) {
                $query->forBatch();
            })
            ->latest();

        if (!in_array($roleId, [1, 2, 8])) {
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
            $uploadsQuery->whereIn('uploaded_by', $visibleUserIds);
        }

        if ($schoolId) {
            $schoolBelongsToActiveBatch = School::forBatch()
                ->whereKey($schoolId)
                ->exists();

            if ($schoolBelongsToActiveBatch) {
                $uploadsQuery->where('school_id', $schoolId);
            } else {
                $uploadsQuery->whereRaw('1 = 0');
            }
        }

        $uploads = $uploadsQuery->get();

        return view('studentattendancelist', compact('uploads', 'schools', 'districts'));
    }
    public function edit($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        return response()->json([
            'upload_id' => $upload->upload_id,
            'file_type' => $upload->file_type,
            'file_name' => $upload->file_name,
            'onedrive_url' => $upload->onedrive_url,
        ]);
    }

    public function update(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $request->validate([
            'attendance_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'trainer_image'   => 'nullable|image|max:10240',
        ]);

        $userId   = Auth::id();
        $schoolId = $upload->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        // Replace Attendance File
        if ($request->hasFile('attendance_file')) {

            $existingPath = $upload->onedrive_path;
            if (is_array($existingPath)) {
                $existingPath = $existingPath[0] ?? null;
            }

            if (!empty($existingPath)) {
                try {
                    $this->oneDrive->deleteFile($existingPath);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old from OneDrive: " . $e->getMessage());
                }
            }

            $file = $request->file('attendance_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "EmergingTech/{$districtName}/{$schoolName}/attendance_sheet";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            $upload->file_name = [$file->getClientOriginalName()];
            $upload->onedrive_path = [$result['path']];
            $upload->onedrive_url = [$result['url'] ?? null];
        }

        // Replace Trainer Image
        if ($request->hasFile('trainer_image')) {

            $existingPath = $upload->onedrive_path;
            if (is_array($existingPath)) {
                $existingPath = $existingPath[0] ?? null;
            }

            if (!empty($existingPath)) {
                try {
                    $this->oneDrive->deleteFile($existingPath);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old from OneDrive: " . $e->getMessage());
                }
            }

            $file = $request->file('trainer_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "EmergingTech/{$districtName}/{$schoolName}/trainer_photo";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            $upload->file_name = [$file->getClientOriginalName()];
            $upload->onedrive_path = [$result['path']];
            $upload->onedrive_url = [$result['url'] ?? null];
        }

        $upload->save();

        return redirect()->route('attendance.list')->with('success', 'File updated successfully!');
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
    public function previewFiles(Request $request)
    {
        $path = $request->query('path');
        $filename = $request->query('filename', 'document');
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
        
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
            return response($response->body(), 200)->header('Content-Type', 'application/pdf')->header(
                'Content-Disposition',
                'inline; filename="' . $filename . '.pdf"'
            );
        } elseif (str_contains($contentType, 'image')) {
            $extension = match (true) {
                str_contains($contentType, 'jpeg') => 'jpg',
                str_contains($contentType, 'png')  => 'png',
                str_contains($contentType, 'webp') => 'webp',
                default => 'jpg',
            };

            return response($response->body(), 200)
                ->header('Content-Type', $contentType)
                ->header(
                    'Content-Disposition',
                    'inline; filename="' . $filename . '.' . $extension . '"'
                );
        } elseif (str_contains($contentType, 'video')) {
            $extension = match (true) {
                str_contains($contentType, 'mp4')  => 'mp4',
                str_contains($contentType, 'webm') => 'webm',
                str_contains($contentType, 'ogg')  => 'ogg',
                str_contains($contentType, 'mov')  => 'mov',
                default => 'mp4',
            };

            return response($response->body(), 200)
                ->header('Content-Type', $contentType)
                ->header(
                    'Content-Disposition',
                    'inline; filename="' . $filename . '.' . $extension . '"'
                );
        } else {
            return response('Unsupported file type', 415);
        }
    }
}
