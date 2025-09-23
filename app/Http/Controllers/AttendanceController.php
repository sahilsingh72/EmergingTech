<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use App\Models\TrainingUpload;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function attendance(){
        $schools = School::all();
        return view('studentattendance', compact('schools'));
    }
    public function upload(Request $request)
    
    {
        $request->validate([
        'school_id'        => 'required|integer',
        'training_date'    => 'required|date',
        'attendance_files' => 'required|array',
        'attendance_files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:4096',
        'trainer_image'    => 'nullable|image|max:4096'
    ]);

    $schoolId = $request->school_id;
    $userId   = Auth::id();

    $attendanceFileNames = [];
    $attendancePaths     = [];
    $attendanceUrls      = [];

    // --- Upload Attendance Files (each file = one row) ---
        if ($request->hasFile('attendance_files')) {
            foreach ($request->file('attendance_files') as $file) {
                
                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "School_{$schoolId}/User_{$userId}/attendance_sheet";

                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);
                $fileTypeMap = config('filetypes');
                $attendanceFileNames[] = $file->getClientOriginalName();
                $attendancePaths[]     = $result['path'];
                $attendanceUrls[]      = $result['url'] ?? null;
                
            }
                TrainingUpload::create([
                    'school_id'      => $schoolId,
                    'coordinator_id' => $userId,
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
            $folder = "School_{$schoolId}/User_{$userId}/trainer_photo";

            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);
            $fileTypeMap = config('filetypes');
            TrainingUpload::create([
                'school_id'      => $schoolId,
                'coordinator_id' => $userId,
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


        return back()->with('success', 'Attendance and trainer image uploaded successfully!');
    }
}
