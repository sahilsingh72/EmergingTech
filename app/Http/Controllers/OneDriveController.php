<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OneDriveService;

class OneDriveController extends Controller
{
    public function redirectToProvider(OneDriveService $oneDrive)
    {
        return redirect($oneDrive->getAuthUrl());
    }

    public function handleCallback(Request $request, OneDriveService $oneDrive)
    {
        if ($request->has('error')) {
            return "❌ Error: " . $request->get('error_description');
        }

        $tokens = $oneDrive->getTokenFromCode($request->get('code'));

        return redirect()->route('onedrive.upload.form')
                         ->with('status', '✅ Tokens saved, you can now upload files!');
    }

    public function showUploadForm()
    {
        return view('onedrive.upload');
    }

    public function uploadFile(Request $request, OneDriveService $oneDrive)
    {
        $request->validate([
            'upload' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('upload');
        $result = $oneDrive->uploadDirect($file, "MyMedia");

        return back()->with('status', $result);
    }
}
