<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\TrainingUpload;
use App\Services\OneDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TrainingEvidenceController extends Controller
{

    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function trainingphotos()
    {
        
        $userId   = Auth::id();
        //  Check if this user already uploaded training photos
        $existingUpload = TrainingUpload::where('uploaded_by', $userId)
            ->where('file_type', 'training_photo')
            ->first();

        if ($existingUpload) {
            return redirect()->route('trainingphotos.list')
                ->with('info', 'You have already uploaded your training photos.');
        }
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

        return  redirect()->route('trainingphotos.list')->with('success', 'training photo uploaded successfully!');
    }

    public function trainingphotoslist(){
        $user = Auth::user();
        $uploads = TrainingUpload::where('uploaded_by', $user->id)->latest()->get();

        return view('trainingphotoslist', compact('uploads'));
    }

    public function editTrainingPhoto($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        // Make sure it’s a training_photo type
        if ($upload->file_type !== 'training_photo') {
            abort(403, 'Invalid file type');
        }

        return response()->json($upload); // we’ll load it dynamically in modal via JS
    }


    public function updateTrainingPhoto(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $existingNames = $upload->file_name;
        $existingPaths = $upload->onedrive_path;
        $existingUrls  = $upload->onedrive_url;

        // Remove selected files (optional)
        if ($request->has('remove_files')) {
            foreach ($request->remove_files as $remove) {
                $index = array_search($remove, $existingNames);
                if ($index !== false) {
                    unset($existingNames[$index]);
                    unset($existingPaths[$index]);
                    unset($existingUrls[$index]);
                }
            }
        }

        // Upload new files
        if ($request->hasFile('new_training_photo')) {
            foreach ($request->file('new_training_photo') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "School_{$upload->school_id}/User_{$upload->uploaded_by}/training_photo";
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
        $thumbnail = $this->oneDrive->getThumbnailUrl($path);

        // Stream the image directly
        return response()->stream(function () use ($thumbnail) {
            $response = Http::withHeaders($thumbnail['headers'])->get($thumbnail['thumbnail_url']);
            echo $response->body();
        }, 200, ['Content-Type' => 'image/jpeg']);
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

    public function trainingvideoslist(){
        $user = Auth::user();
        $uploads = TrainingUpload::where('uploaded_by', $user->id)->latest()->get();

        return view('trainingvideoslist', compact('uploads'));
    }

    public function editTrainingVideo($id)
    {
        $upload = TrainingUpload::findOrFail($id);

        // Make sure it’s a training_photo type
        if ($upload->file_type !== 'training_video') {
            abort(403, 'Invalid file type');
        }

        return response()->json($upload); // we’ll load it dynamically in modal via JS
    }


    public function updateTrainingVideo(Request $request, $id)
    {
        $upload = TrainingUpload::findOrFail($id);

        $existingNames = $upload->file_name;
        $existingPaths = $upload->onedrive_path;
        $existingUrls  = $upload->onedrive_url;

        // Remove selected files (optional)
        if ($request->has('remove_files')) {
            foreach ($request->remove_files as $remove) {
                $index = array_search($remove, $existingNames);
                if ($index !== false) {
                    unset($existingNames[$index]);
                    unset($existingPaths[$index]);
                    unset($existingUrls[$index]);
                }
            }
        }

        // Upload new files
        if ($request->hasFile('new_training_video')) {
            foreach ($request->file('new_training_video') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $folder = "School_{$upload->school_id}/User_{$upload->uploaded_by}/training_video";
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
