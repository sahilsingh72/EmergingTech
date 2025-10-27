<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\School;
use App\Models\StudentMst;
use App\Models\Trainer;
use App\Models\TrainingUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user(); // full user object
        $userId = $user->id;  // just the ID
        $roleName = $user->role->name;
        $districtId = $user->district_id;
        $students = 0;

        if ($roleName === 'OCAC' || $roleName === 'OKCL') {
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



        $totalSchools = School::count();
        $completedSchools = School::where('training_completed', 1)->count();
        $schools = School::all();


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
