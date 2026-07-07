<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\TrainingUpload;
use App\Services\OneDriveService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GalleryController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function gallery(Request $request)
    {

        $districtId = $request->get('district_id');
        $schoolId = $request->get('school_id');

        $districts = District::select('DSM_DSCD', 'DSM_DSNM')
            ->orderBy('DSM_DSNM')
            ->get();

        $schools = collect();
        if ($districtId) {
            $schools = School::forBatch()->where('scm_dist_id', $districtId)
                ->select('scm_id', 'scm_name')
                ->orderBy('scm_name')
                ->get();
        }

        $media = TrainingUpload::when($districtId, function ($q) use ($districtId) {
            $q->whereHas('school', function ($s) use ($districtId) {
                $s->where('scm_dist_id', $districtId);
            });
        })
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('gallery.gallery-index', compact('districts', 'schools', 'districtId', 'schoolId', 'media'));
    }
    public function getSchoolsByDistrict($districtId)
    {
        return School::where('scm_dist_id', $districtId)
            ->select('scm_id', 'scm_name')
            ->orderBy('scm_name')
            ->get();
    }
    public function previewFile(Request $request)
    {
        $path = $request->query('path');
        $mime = $request->query('mime'); // optional

        if (!$path) {
            return response('Missing file path', 400);
        }

        try {
            $fileInfo = $this->oneDrive->getFileInfo($path);

            if (!isset($fileInfo['@microsoft.graph.downloadUrl'])) {
                return response('Download URL not found', 404);
            }

            $downloadUrl = $fileInfo['@microsoft.graph.downloadUrl'];

            $contentType = $mime ?? ($fileInfo['file']['mimeType'] ?? 'application/octet-stream');

            return response()->stream(function () use ($downloadUrl) {
                $response = Http::get($downloadUrl);
                echo $response->body();
            }, 200, [
                'Content-Type' => $contentType,
                'Cache-Control' => 'no-store',
            ]);
        } catch (\Exception $e) {
            return response('Error fetching file: ' . $e->getMessage(), 500);
        }
    }

    public function previewVideo(Request $request)
    {
        $path = $request->query('path');

        $downloadUrl = Cache::remember("onedrive_video_$path", 300, function () use ($path) {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            return $fileInfo['@microsoft.graph.downloadUrl'] ?? null;
        });

        if (!$downloadUrl) {
            return response('Video not found', 404);
        }

        $response = Http::get($downloadUrl);

        return response($response->body(), 200)
            ->header('Content-Type', 'video/mp4'); // use video/mp4
    }

    public function downloadFile(Request $request)
    {
        $path = $request->query('path');
        $customFilename = $request->query('filename');

        if (!$path) {
            return response('Missing file path', 400);
        }

        try {
            $fileInfo = $this->oneDrive->getFileInfo($path);

            if (!isset($fileInfo['@microsoft.graph.downloadUrl'])) {
                return response('Download URL not found', 404);
            }

            $downloadUrl = $fileInfo['@microsoft.graph.downloadUrl'];

            if ($customFilename) {
                // Ensure .pdf extension
                $fileName = str_ends_with($customFilename, '.pdf')
                    ? $customFilename
                    : $customFilename . '.pdf';
            } else {
                // Fallback to OneDrive name
                $fileName = $fileInfo['name'] ?? 'file.pdf';
            }

            // ✅ Stream file and force download with correct filename
            return response()->streamDownload(function () use ($downloadUrl) {
                echo Http::get($downloadUrl)->body();
            }, $fileName);
        } catch (\Exception $e) {
            return response('Error downloading file: ' . $e->getMessage(), 500);
        }
    }
}
