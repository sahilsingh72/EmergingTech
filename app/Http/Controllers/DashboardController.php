<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\PhotoGallery;
use App\Models\School;
use App\Models\StudentMst;
use App\Models\Trainer;
use App\Models\TrainingUpload;
use App\Models\User;
use App\Services\OneDriveService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function fetchGallery()
    {
        try {
            $images = PhotoGallery::orderBy('created_at', 'desc')
                ->take(30)
                ->get(['onedrive_path', 'file_name']);
            // ->pluck('onedrive_url');

            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function photogallery(Request $request)
    {

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'school_id' => 'required|integer',
        ]);

        $file = $request->file('file');
        $userId = Auth::id();
        $schoolId = $request->school_id;
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder = "Photogallery/School_{$schoolId}/User_{$userId}";

        try {
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            PhotoGallery::create([
                'uploaded_by' => $userId,
                'school_id' => $schoolId,
                'file_name' => $file->getClientOriginalName(),
                'onedrive_url' => $result['url'],
                'onedrive_path' => $result['path'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully to photogallery!',
                'url' => $result['url'] ?? null,
                'path' => $result['path'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function previewImage(Request $request)
    {
        $path = $request->query('path');
        if (!$path) {
            return response('Missing path', 400);
        }

        try {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            if (!isset($fileInfo['@microsoft.graph.downloadUrl'])) {
                return response('Download URL not found', 404);
            }

            $downloadUrl = $fileInfo['@microsoft.graph.downloadUrl'];

            return response()->stream(function () use ($downloadUrl) {
                $response = Http::get($downloadUrl);
                echo $response->body();
            }, 200, ['Content-Type' => 'image/jpeg']);
        } catch (\Exception $e) {
            return response('Error fetching image: ' . $e->getMessage(), 500);
        }
    }

    public function dashboard()
    {
        $user = Auth::user(); // full user object
        $userId = $user->id;  // just the ID
        $roleName = $user->role->name;
        $districtId = $user->district_id;
        $students = 0;

        if ($roleName === 'OCAC' || $roleName === 'OKCL' || $roleName === 'Accounts') {
            // ✅ OCAC or OKCL: see all students
            $students = StudentMst::count();
        } else {
            // ✅ DLC / Coordinator / Trainer: only students in their district
            $students = StudentMst::where('stu_distid', $districtId)->count();
        }

        if ($roleName === 'DLC') {
            $totalCoordinators = Coordinator::whereHas('user', function ($query) use ($userId) {
                $query->where('assignUnder_id', $userId);
            })->count();
            $totalTrainers = Trainer::whereHas('user', function ($query) use ($userId) {
                $query->where('assignUnder_id', $userId);
            })->count();
        } elseif ($roleName === 'Coordinator' || $roleName === 'Trainer') {

            $dlcId = $user->assignUnder_id;

            $totalTrainers = Trainer::whereHas('user', function ($query) use ($dlcId) {
                $query->where('assignUnder_id', $dlcId);
            })->count();
            $totalCoordinators = Coordinator::whereHas('user', function ($query) use ($dlcId) {
                $query->where('assignUnder_id', $dlcId);
            })->count();
        } elseif ($roleName === 'OCAC' || 'OKCL') {
            $totalCoordinators = Coordinator::count();
            $totalTrainers = Trainer::count();
        }

        $zoneWise = School::select('scm_zone_id')
            ->selectRaw('COUNT(*) as total, SUM(training_completed) as completed')
            ->groupBy('scm_zone_id')
            ->get();

        // District-wise completed trainings
        $districtWise = School::select('scm_dist_id')
            ->selectRaw('COUNT(*) as total, SUM(training_completed) as completed')
            ->groupBy('scm_dist_id')
            ->get();


        $roleId = $user->role_id;
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        $totalSchools = School::count();
        $completedSchools = School::where('training_completed', 1)->count();


        return view('dashboard', compact(
            'totalCoordinators',
            'totalTrainers',
            'totalSchools',
            'completedSchools',
            'zoneWise',
            'districtWise',
            'students',
            'schools',
        ));
    }

    public function getDistrictProgress()
    {
        $nameCorrections = [
            'Keonjhar' => 'Kendujhar',
            'Sundergarh' => 'Sundargarh',
            'Sonepur' => 'Subarnapur',
            'Bolangir' => 'Balangir',
            'Nabarangpur' => 'Nabarangapur',
        ];
        $districts = School::select('scm_dist')
            ->groupBy('scm_dist')
            ->get()
            ->pluck('scm_dist');

        $totalSchoolData = [];
        $completedData = [];
        $studentData = [];

        foreach ($districts as $district) {
            $geoDistrict = $nameCorrections[$district] ?? $district;
            $totalSchools = School::where('scm_dist', $district)->count();
            $completed = School::where('scm_dist', $district)
                ->where('training_completed', 1)
                ->count();

            $totalStudents = StudentMst::where('stu_distid', function ($q) use ($district) {
                $q->select('scm_dist_id')
                    ->from('school_mst')
                    ->where('scm_dist', $district)
                    ->limit(1);
            })->count();
            
            $totalSchoolData[$geoDistrict] = $totalSchools;
            $completedData[$geoDistrict] = $completed;
            $studentData[$geoDistrict] = $totalStudents;
        }

        return response()->json([
            'total_schools' => $totalSchoolData,
            'completed' => $completedData,
            'students' => $studentData,
        ]);
    }


    public function getChartData(Request $request)
    {
        $filter = $request->query('filter', 'day');
        $start = $request->query('start');
        $end = $request->query('end');

        // cache key for efficiency
        $cacheKey = "chart_data_{$filter}_" . md5($start . $end);
        return Cache::remember($cacheKey, 300, function () use ($filter, $start, $end) {

            $query = TrainingUpload::query();

            switch ($filter) {
                case 'day':
                    $query->whereDate('created_at', Carbon::today());
                    $groupFormat = '%H:00';
                    break;

                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    $groupFormat = '%a';
                    break;

                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    $groupFormat = '%d';
                    break;

                case 'custom':
                    $query->whereBetween('created_at', [Carbon::parse($start), Carbon::parse($end)]);
                    $groupFormat = '%d %b';
                    break;

                default:
                    $query->whereDate('created_at', Carbon::today());
                    $groupFormat = '%H:00';
            }

            $uploads = $query
                ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as label, file_type, COUNT(*) as total")
                ->groupBy('label', 'file_type')
                ->orderBy('label')
                ->get();

            // group by label (time/day)
            $grouped = $uploads->groupBy('label');

            $labels = $grouped->keys();
            $fileTypes = $uploads->pluck('file_type')->unique();

            // structure datasets per file type
            $datasets = [];
            foreach ($fileTypes as $index => $type) {
                $datasets[] = [
                    'label' => ucfirst(str_replace('_', ' ', $type)),
                    'data' => $labels->map(
                        fn($label) =>
                        optional($grouped[$label]->firstWhere('file_type', $type))->total ?? 0
                    )->values(),
                    'backgroundColor' => self::colorPalette($index, 0.6),
                    'borderColor' => self::colorPalette($index, 1),
                    'borderWidth' => 1
                ];
            }

            return [
                'labels' => $labels->values(),
                'datasets' => $datasets,
            ];
        });
    }

    // simple color palette generator
    private static function colorPalette($index, $opacity = 1)
    {
        $colors = [
            "rgba(255, 99, 132, {$opacity})",
            "rgba(54, 162, 235, {$opacity})",
            "rgba(255, 206, 86, {$opacity})",
            "rgba(75, 192, 192, {$opacity})",
            "rgba(153, 102, 255, {$opacity})",
            "rgba(255, 159, 64, {$opacity})",
            "rgba(100, 181, 246, {$opacity})",
            "rgba(255, 138, 101, {$opacity})"
        ];
        return $colors[$index % count($colors)];
    }
    public function getCampCompletionChartData(Request $request)
    {
        $filter = $request->query('filter', 'day'); // day, week, month, custom
        $zoneId = $request->query('zone_id');
        $distId = $request->query('dist_id');
        $start = $request->query('start');
        $end = $request->query('end');

        $cacheKey = "camp_completion_{$filter}_{$zoneId}_{$distId}_" . md5($start . $end);

        return Cache::remember($cacheKey, 300, function () use ($filter, $zoneId, $distId, $start, $end) {

            $query = School::query();

            // Filter by zone or district if provided
            if ($zoneId) $query->where('scm_zone_id', $zoneId);
            if ($distId) $query->where('scm_dist_id', $distId);

            $schools = $query->get();

            $labels = [];
            $completed = [];

            switch ($filter) {
                case 'day':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today();
                    break;

                case 'week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;

                case 'month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;

                case 'custom':
                    $startDate = Carbon::parse($start);
                    $endDate = Carbon::parse($end);
                    break;

                default:
                    $startDate = Carbon::today();
                    $endDate = Carbon::today();
            }

            // Build day labels
            for ($d = $startDate->copy(); $d <= $endDate; $d->addDay()) {
                $labels[] = $d->format('Y-m-d');

                // Count schools completed on that day
                $count = TrainingUpload::whereIn('school_id', $schools->pluck('scm_id'))
                    ->where('file_type', 'training_completion_certificate')
                    ->whereDate('training_date', $d->format('Y-m-d'))
                    ->count();

                $completed[] = $count;
            }

            return [
                'labels' => $labels,
                'completed' => $completed
            ];
        });
    }
}
