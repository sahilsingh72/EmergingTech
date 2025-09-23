<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\TrainingUpload;
use App\Services\OneDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingEvidenceController extends Controller
{

    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function trainingphotos()
    {
        $schools = School::all();
        return view('trainingphotos', compact('schools'));
    }

    public function upload(Request $request)

    {

        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'training_photo' => 'required|array',
            'training_photo.*' => 'file|mimes:jpg,jpeg,png|max:4096',
            'description'     => 'nullable|string',
        ]);

        $schoolId = $request->school_id;
        $userId   = Auth::id();

        $fileTypeMap = config('filetypes');

        $fileNames = [];
        $filePaths = [];
        $fileUrls  = [];


        if ($request->hasFile('training_photo')) {
            foreach ($request->file('training_photo') as $file) {

                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "School_{$schoolId}/User_{$userId}/training_photo";

                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

                $fileNames[] = $file->getClientOriginalName();
                $filePaths[] = $result['path'];
                $fileUrls[]  = $result['url'] ?? null;
            }
            TrainingUpload::create([
                'school_id'      => $schoolId,
                'coordinator_id' => $userId,
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

        return back()->with('success', 'training photo uploaded successfully!');
    }
    public function trainingvideos()
    {
        $schools = School::all();
        return view('trainingvideos', compact('schools'));
    }
    public function uploadvideo(Request $request)

    {
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'training_video' => 'required|array',
            'training_video.*' => 'mimes:mp4,avi,mov,mkv|max:10240',
            'description'     => 'nullable|string',
        ]);

        $schoolId = $request->school_id;
        $userId   = Auth::id();

        $fileTypeMap = config('filetypes');

        $fileNames = [];
        $filePaths = [];
        $fileUrls  = [];


        foreach ($request->file('training_video') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder   = "School_{$schoolId}/User_{$userId}/training_video";

            // Upload to OneDrive
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            // Collect values
            $fileNames[] = $file->getClientOriginalName();
            $filePaths[] = $result['path'] ?? null;
            $fileUrls[]  = $result['url'] ?? null;
        }
        TrainingUpload::create([
            'school_id'      => $schoolId,
            'coordinator_id' => $userId,
            'file_type'      => 'training_video',
            'filetype_id'    => $fileTypeMap['training_video'] ?? null,
            'file_name'      => $fileNames,
            'onedrive_path'  => $filePaths,
            'onedrive_url'   => $fileUrls,
            'uploaded_by'    => $userId,
            'training_date'  => $request->training_date,
            'description'    => $request->description,
        ]);

        return back()->with('success', 'training video uploaded successfully!');
    }

    public function trainingcompcertificate()
    {
        $schools = School::all();
        return view('trainingcompcertificate', compact('schools'));
    }
    public function uploadCcertificate(Request $request)

    {
        // dd($request->all());
        $request->validate([
            'school_id'        => 'required|integer',
            'training_date'    => 'required|date',
            'training_completion_certificate.*' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $schoolId = $request->school_id;
        $userId   = Auth::id();

        $fileTypeMap = config('filetypes');


        $file = $request->file('training_completion_certificate');
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder   = "School_{$schoolId}/User_{$userId}/training_completion_certificate";

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

        return back()->with('success', 'training completion certificate uploaded successfully!');
    }
}
