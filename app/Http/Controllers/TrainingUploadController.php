<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingUpload;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Auth;

class TrainingUploadController extends Controller
{
   protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    

   

    

  


    /**
     * Upload Bills
     */
    public function uploadBills(Request $request)
    {
        return $this->uploadFile($request, 'bills');
    }


    /**
     * General function to handle file upload
     */
    private function uploadFile(Request $request, $fileType, $inputName = 'file')
    {
        $request->validate([
            'school_id'    => 'required|integer',
            'training_date'=> 'nullable|date',
            'description'  => 'nullable|string',
            $inputName     => 'required',
            $inputName . '.*' => 'file|max:2048'
        ]);

        $files = $request->file($inputName);
         if (!is_array($files)) {
            $files = [$files]; // wrap single file in array
        }

        // Folder structure: School_X/User_Y/fileType
        $folder = "School_{$request->school_id}/User_" . Auth::id() . "/{$fileType}";

        foreach ($files as $file) {
            $result = $this->oneDrive->uploadDirect($file, $folder);

            // Store in DB
            TrainingUpload::create([
                'school_id'      => $request->school_id,
                'coordinator_id' => Auth::id(), // or trainer ID if needed
                'file_type'      => $fileType,
                'file_name'      => $file->getClientOriginalName(),
                'onedrive_path'  => $result['path'],
                'onedrive_url'   => $result['url'] ?? null,
                'uploaded_by'    => Auth::id(),
                'training_date'  => $request->training_date,
                'description'    => $request->description,
            ]);

            return redirect()->back()->with('success', "File uploaded successfully to OneDrive.");

        } 
    }
}
