<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\TrainingUpload;
use Illuminate\Http\Request;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }
    public function writtenfeedback()
    {
        $schools = School::all();
        return view('writtenfeedback', compact('schools'));
    }
    public function uploadwrittenfeedback(Request $request)

    {
        // dd($request->all());
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'written_feedback' => 'required|mimes:pdf|max:2048',
        ]);

        $schoolId = $request->school_id;
        $userId   = Auth::id();

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

    public function uploadfeedback()
    {
        $schools = School::all();
        return view('uploadfeedback', compact('schools'));
    }

    public function uploadvideofeedback(Request $request)

    {

        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'designation'    => 'required|string',
            'video_feedback' => 'required|mimes:mp4,mov,avi,wmv|max:51200',
            'description'     => 'nullable|string',
        ]);

        $schoolId = $request->school_id;
        $userId   = Auth::id();

        $fileTypeMap = config('filetypes');


        $file = $request->file('video_feedback');
            $filename = time() . '_' . $file->getClientOriginalName();
            $designation = $request->designation;
            $folder   = "School_{$schoolId}/User_{$userId}/video_feedback/designation_{$designation}";

            // Upload to OneDrive
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

        TrainingUpload::create([
            'school_id'      => $schoolId,
            'coordinator_id' => $userId,
            'file_type'      => 'video_feedback',
            'filetype_id'    => $fileTypeMap['video_feedback'] ?? null,
            'file_name'      => $file->getClientOriginalName(),
            'onedrive_path'  => $result['path'] ?? null,
            'onedrive_url'   => $result['url'] ?? null,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
        ]);

        return back()->with('success', 'Feedback video uploaded successfully!');
    }

    public function onlinefeedback()
    {
        $schools = School::all();
        return view('onlinefeedback', compact('schools'));
    }
}
