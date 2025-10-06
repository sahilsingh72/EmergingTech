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
        $userId = Auth::id();

        // Check if user already uploaded attendance & trainer image
        $uploads = TrainingUpload::where('uploaded_by', $userId)
            ->whereIn('file_type', ['attendance_sheet', 'trainer_photo'])
            ->get();

        if ($uploads->count() >= 2) {
            // User already uploaded both files, redirect to list
            return redirect()->route('attendance.list')
                            ->with('info', 'You have already uploaded attendance and trainer image.');
        }
        
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
        'trainer_image'    => 'required|image|max:4096'
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


        return  redirect()->route('attendance.list')->with('success', 'Attendance and trainer image uploaded successfully!');
        // return  back()->with('success', 'Attendance and trainer image uploaded successfully!');
    }
    public function attendanceList(){
        $user = Auth::user();
        $uploads = TrainingUpload::where('uploaded_by', $user->id)->latest()->get();

        return view('studentattendancelist', compact('uploads'));
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
        'attendance_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        'trainer_image'   => 'nullable|image|max:4096',
    ]);

    $userId   = Auth::id();
    $schoolId = $upload->school_id;

    // Replace Attendance File
    if ($request->hasFile('attendance_file')) {
        $file = $request->file('attendance_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder = "School_{$schoolId}/User_{$userId}/attendance_sheet";

        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

        $upload->file_name = [$file->getClientOriginalName()];
        $upload->onedrive_path = [$result['path']];
        $upload->onedrive_url = [$result['url'] ?? null];
    }

    // Replace Trainer Image
    if ($request->hasFile('trainer_image')) {
        $file = $request->file('trainer_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder = "School_{$schoolId}/User_{$userId}/trainer_photo";

        $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

        $upload->file_name = [$file->getClientOriginalName()];
        $upload->onedrive_path = [$result['path']];
        $upload->onedrive_url = [$result['url'] ?? null];
    }

    $upload->save();

    return redirect()->route('attendance.list')->with('success', '✅ File updated successfully!');
}


}
