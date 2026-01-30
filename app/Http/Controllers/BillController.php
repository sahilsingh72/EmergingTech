<?php

namespace App\Http\Controllers;

use App\Models\CampExpenseBill;
use App\Models\Coordinator;
use App\Models\District;
use App\Models\InstituteFeedback;
use App\Models\Role;
use App\Models\School;
use App\Models\StudentFeedback;
use App\Models\StudentMst;
use App\Models\SuppStaff;
use App\Models\Trainer;
use App\Models\TrainerTravelBill;
use App\Models\TrainingUpload;
use App\Models\User;
use App\Services\OneDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function staffexpense()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $roles = Role::whereIn('name', ['DLC', 'Coordinator', 'Trainer', 'Supporting Staff'])->get();

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('staffexpense', compact('schools', 'roles'));
    }

    public function getStaff($schoolId, $roleId)
    {
        $role = Role::find($roleId)->name;

        switch ($role) {

            case 'DLC':
                $school = School::find($schoolId);
                $staff = User::where('role_id', $roleId)
                    ->where('district_id', $school->scm_dist_id)
                    ->select('id', 'name')
                    ->get();
                break;


            case 'Coordinator':
                $staff = Coordinator::join('coordinator_scm_allocation', 'coordinator_scm_allocation.coordinator_id', '=', 'coordinator_mst.coordinator_id')
                    ->where('coordinator_scm_allocation.scm_id', $schoolId)
                    ->select('coordinator_mst.coordinator_id AS id', 'coordinator_mst.coordinator_name AS name')
                    ->get();
                break;


            case 'Trainer':
                $staff = Trainer::join('trainer_scm_allocation', 'trainer_scm_allocation.trainer_id', '=', 'trainers.trainer_id')
                    ->where('trainer_scm_allocation.scm_id', $schoolId)
                    ->select('trainers.trainer_id AS id', 'trainers.trainer_name AS name')
                    ->get();
                break;


            case 'Supporting Staff':
                $staff = SuppStaff::join('staff_scm_allocation', 'staff_scm_allocation.staff_id', '=', 'support_staff_mst.ss_id')
                    ->where('staff_scm_allocation.scm_id', $schoolId)
                    ->select('support_staff_mst.ss_id AS id', 'support_staff_mst.ss_name AS name')
                    ->get();
                break;


            default:
                $staff = collect();
        }

        return response()->json($staff);
    }

    public function foodExpense()
    {
        $user = Auth::user();
        $roleId = $user->role_id;

        $userId = $user->id;
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('bills.foodbills', compact('schools'));
    }

    public function foodExpenseStore(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:school_mst,scm_id',
            'bill_type' => 'required|array|min:1',
            'bill_type.*' => 'required|string',
            'training_date' => 'required|array',
            'training_date.*' => 'required|date',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1',
            'bill_file' => 'required|array',
            'bill_file.*' => 'required|file|mimes:pdf|max:5120',
        ]);

        $userId = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $billTypes = $request->bill_type;
        $dates = $request->training_date;
        $amounts = $request->amount;
        $files = $request->file('bill_file');

        foreach ($billTypes as $i => $type) {

            $billType = 'Camp Fooding';

            $trainingDate = $dates[$i];
            $newAmount = $amounts[$i];

            $existingTotal = CampExpenseBill::where('school_id', $schoolId)
                ->where('bill_type', 'Camp Fooding')
                ->where('training_date', $trainingDate)
                ->sum('amount');

            if (($existingTotal + $newAmount) > 17500) {
                return back()->with(
                    'error',
                    'Total food bill amount for this school cannot exceed ₹17,500.'
                );
            }
            $file = $files[$i];
            $fileName = time() . '_' . $file->getClientOriginalName();

            $folder = "EmergingTech/{$districtName}/{$schoolName}/Camp_Expenses/{$billType}";
            $upload = $this->oneDrive->uploadDirect($file, $folder, $fileName);

            CampExpenseBill::create([
                'school_id'     => $request->school_id,
                'uploaded_by'   => $userId,
                'bill_type'     => $billType,
                'training_date' => $trainingDate,
                'amount'        => $newAmount,
                'bill_path'     => $upload['path'] ?? null,
                'bill_url'      => $upload['url'] ?? null,
            ]);
        }

        return back()->with('success', 'Food bill submitted successfully.');
    }
    public function foodExpenseList(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;

        $districtId = $request->district_id;
        $districts = District::orderBy('DSM_DSNM')->get();

        $query = CampExpenseBill::with(['school', 'uploadedBy'])
            ->join('school_mst', 'school_mst.scm_id', '=', 'camp_expense_bills.school_id')
            ->select('camp_expense_bills.*')
            ->where('camp_expense_bills.bill_type',  'camp fooding')
            ->orderBy('school_mst.scm_dist', 'ASC')
            ->orderBy('school_mst.scm_name', 'ASC');

        if (in_array($role, ['Accounts', 'OKCL']) && $request->district_id) {
            $query->where('school_mst.scm_dist_id', $request->district_id);
        }

        // DLC → only own district
        if ($role === 'DLC') {
            $query->where('school_mst.scm_dist_id', $user->district_id);
        }

        // Status filter (optional – if you add later)
        if ($request->status) {
            $query->where('camp_expense_bills.status', $request->status);
        }

        $records = $query->get();

        $schoolTotals = $records
            ->groupBy('school_id')
            ->map(fn($rows) => $rows->sum('amount'));

        $billTypes = [
            'Camp Fooding',
        ];

        $schoolProgress = $records->groupBy('school_id')->map(function ($rows) {
            $schoolId = $rows->first()->school_id;
            $uploads = TrainingUpload::where('school_id', $schoolId)->pluck('file_type')->unique();
            return [
                'attendance' => $uploads->contains('attendance_sheet'), 
                'photos' => $uploads->contains('training_photo'), 
                'video' => $uploads->contains('training_video'), 
                'institute_feedback' => $uploads->contains('institute_feedback'), 
                'video_feedback' => $uploads->contains('video_feedback'), 
                'certificate' => $uploads->contains('training_completion_certificate'),
            ];
        });

        return view('bills.foodbillslist', compact('records', 'districts', 'districtId', 'schoolTotals', 'billTypes', 'schoolProgress'));
    }
    public function getTrainingUploads($schoolId, $type)
    {
        $uploads = TrainingUpload::where('school_id', $schoolId)
            ->where('file_type', $type)
            ->get();

        $files = collect();

        foreach ($uploads as $upload) {

            // Normalize URL
            $urls = is_array($upload->onedrive_url)
                ? $upload->onedrive_url
                : [$upload->onedrive_url];

            // Normalize Path
            $paths = is_array($upload->onedrive_path)
                ? $upload->onedrive_path
                : [$upload->onedrive_path];

            foreach ($urls as $index => $url) {
                if (!$url) continue;

                $files->push([
                    'file_url'   => $url,
                    'file_path'  => $paths[$index] ?? $paths[0] ?? null,
                    'file_type'  => $upload->file_type,
                    'school'     => $upload->school->scm_name,
                    'created_at' => $upload->created_at,
                ]);
            }
        }

        return response()->json($files->values());
    }

    public function previewFiles(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            return response('Invalid file path', 400);
        }

        $fileInfo = $this->oneDrive->getFileInfo($path);

        if (!isset($fileInfo['@microsoft.graph.downloadUrl'])) {
            return response('Download URL not found', 404);
        }

        $downloadUrl = $fileInfo['@microsoft.graph.downloadUrl'];

        // Detect content type from OneDrive
        $head = Http::head($downloadUrl);
        $contentType = $head->header('Content-Type', 'application/octet-stream');

        return response()->stream(function () use ($downloadUrl) {
            echo Http::get($downloadUrl)->body();
        }, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline', //  opens in new tab
        ]);
    }
    public function foodBillSlip($id)
    {
        $bill = CampExpenseBill::with('school', 'uploadedBy')
            ->where('bill_type', 'Camp Fooding')
            ->findOrFail($id);

        return view('bills.food_bill_slip', compact('bill'));
    }
    public function uploadcampexpense()
    {
        $user = Auth::user();
        $roleId = $user->role_id;

        $userId = $user->id;
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist', 'training_date')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }
        return view('bills.campexpensebills', compact('schools'));
    }
    public function campexpenseStore(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:school_mst,scm_id',
            'bill_type' => 'required|array|min:1',
            'bill_type.*' => 'required|string',
            'training_date' => 'required|array',
            'training_date.*' => 'required|date',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1',
            'bill_file' => 'required|array',
            'bill_file.*' => 'required|file|mimes:pdf|max:5120',
            'custom_bill_type' => 'nullable|array',
        ]);

        $userId = Auth::id();
        $schoolId = $request->school_id;
        $school = School::find($schoolId);
        $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

        $billTypes = $request->bill_type;
        $customTypes = $request->custom_bill_type;
        $dates = $request->training_date;
        $amounts = $request->amount;
        $files = $request->file('bill_file');

        foreach ($billTypes as $i => $type) {

            $billType = $type === 'Misc'
                ? ($customTypes[$i] ?? 'Misc')
                : $type;

            $amount = $amounts[$i];

            $limits = [
                'Inauguration' => 1200,
                'Generator'    => 5000,
            ];

            $existingTotal = CampExpenseBill::where('school_id', $schoolId)
                ->where('bill_type', $billType)
                ->where('training_date', $dates[$i])
                ->sum('amount');

            $newTotal = $existingTotal + $amount;

            if (isset($limits[$billType]) && $newTotal > $limits[$billType]) {
                return back()
                    ->withErrors([
                        'amount' => "{$billType} total amount cannot exceed ₹{$limits[$billType]}. Already used ₹{$existingTotal}."
                    ])
                    ->withInput();
            }

            $file = $files[$i];
            $fileName = time() . '_' . $file->getClientOriginalName();

            $folder = "EmergingTech/{$districtName}/{$schoolName}/Camp_Expenses/{$billType}";
            $upload = $this->oneDrive->uploadDirect($file, $folder, $fileName);

            CampExpenseBill::create([
                'school_id'     => $request->school_id,
                'uploaded_by'   => $userId,
                'bill_type'     => $billType,
                'training_date' => $dates[$i],
                'amount'        => $amounts[$i],
                'bill_path'     => $upload['path'] ?? null,
                'bill_url'      => $upload['url'] ?? null,
            ]);
        }

        return back()->with('success', 'Camp expense bills submitted successfully.');
    }
    public function campExpenseList(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;

        $districtId = $request->district_id;
        $districts = District::orderBy('DSM_DSNM')->get();

        $query = CampExpenseBill::with(['school', 'uploadedBy'])
            ->join('school_mst', 'school_mst.scm_id', '=', 'camp_expense_bills.school_id')
            ->select('camp_expense_bills.*')
            ->where('camp_expense_bills.bill_type', '!=', 'Camp Fooding')
            ->orderBy('school_mst.scm_dist', 'ASC')
            ->orderBy('school_mst.scm_name', 'ASC');

        if (in_array($role, ['Accounts', 'OKCL']) && $request->district_id) {
            $query->where('school_mst.scm_dist_id', $request->district_id);
        }

        // DLC → only own district
        if ($role === 'DLC') {
            $query->where('school_mst.scm_dist_id', $user->district_id);
        }

        // Status filter (optional – if you add later)
        if ($request->status) {
            $query->where('camp_expense_bills.status', $request->status);
        }

        $records = $query->get();

        $schoolTotals = $records
            ->groupBy('school_id')
            ->map(fn($rows) => $rows->sum('amount'));

        $billTypes = [
            'Inauguration',
            'Generator',
            'Misc'
        ];

        $schoolProgress = $records->groupBy('school_id')->map(function ($rows) {
            $schoolId = $rows->first()->school_id;
            $uploads = TrainingUpload::where('school_id', $schoolId)->pluck('file_type')->unique();

            $totalStudents = StudentMst::where('stu_scm_id', $schoolId)
                ->where('attendance', 1)
                ->count();

            $studentRatingCount = StudentFeedback::where('school_id', $schoolId)
                ->whereIn('stu_id', function ($q) use ($schoolId) {
                    $q->select('stu_id')
                    ->from('student_mst')
                    ->where('stu_scm_id', $schoolId)
                    ->where('attendance', 1);
                })
                ->count();

            // institute feedback rating
            $hasInstituteRating = InstituteFeedback::where('school_id', $schoolId)->exists();

            return [
                'attendance' => $uploads->contains('attendance_sheet'), 
                'photos' => $uploads->contains('training_photo'), 
                'video' => $uploads->contains('training_video'), 
                'written_feedback' => $uploads->contains('written_feedback'), 
                'student_feedback_rating' => $studentRatingCount >= 2,
                'institute_feedback' => $uploads->contains('institute_feedback'), 
                'institute_feedback_rating' => $hasInstituteRating,
                'video_feedback' => $uploads->contains('video_feedback'), 
                'certificate' => $uploads->contains('training_completion_certificate'),
            ];
        });

        return view('bills.campexpensebillslist', compact('records', 'districts', 'districtId', 'schoolTotals', 'billTypes', 'schoolProgress'));
    }
    public function CampExpensepreview(Request $request)
    {
        $path = $request->query('path');
        if (!$path) {
            return response('Invalid file path', 400);
        }

        $downloadUrl = Cache::remember("onedrive_download_" . md5($path), 300, function () use ($path) {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            return $fileInfo['@microsoft.graph.downloadUrl'] ?? null;
        });

        if (!$downloadUrl) {
            return response('File not found or access denied', 404);
        }

        // Fetch file headers from OneDrive
        $response = Http::head($downloadUrl);
        $contentType = $response->header('Content-Type', 'application/octet-stream');

        // ✅ Only allow PDF files
        if (!str_contains($contentType, 'pdf')) {
            return response('Only PDF preview is supported.', 415);
        }

        // Fetch the PDF content and stream inline
        $pdfContent = Http::get($downloadUrl)->body();

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="CampExpense_bills.pdf"');
    }

    public function CampExpenseapprove(Request $request, $id)
    {
        $bill = CampExpenseBill::findOrFail($id);

        $bill->status = 'Approved';
        $bill->remarks = $request->remarks;
        $bill->status_updated_at = now();
        $bill->status_updated_by = Auth::id();
        $bill->save();

        return back()->with('success', 'bill approved successfully!');
    }
    public function CampExpensereject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:500'
        ]);

        $bill = CampExpenseBill::findOrFail($id);

        $bill->status = 'Rejected';
        $bill->remarks = $request->remarks;
        $bill->status_updated_at = now();
        $bill->status_updated_by = Auth::id();
        $bill->save();

        return back()->with('error', 'bill rejected.');
    }
    public function CampExpenserevert($id)
    {
        $record = CampExpenseBill::findOrFail($id);

        $record->status = 'Pending';
        $record->remarks = null;
        $record->status_updated_at = now();
        $record->status_updated_by = Auth::id();
        $record->save();

        return back()->with('success', 'Status reverted to Pending.');
    }
    public function campexpenseUpdate(Request $request, $id)
    {
        $bill = CampExpenseBill::findOrFail($id);

        $request->validate([
            'bill_type' => 'required|string',
            'custom_bill_type' => 'nullable|string|max:255',
            'training_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'bill_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($bill->bill_type === 'Camp Fooding') {

            $newAmount = $request->amount;

            // Total of other food bills (exclude current one)
            $otherTotal = CampExpenseBill::where('school_id', $bill->school_id)
                ->where('bill_type', 'Camp Fooding')
                ->where('training_date', $bill->training_date)
                ->where('id', '!=', $bill->id)
                ->sum('amount');

            if (($otherTotal + $newAmount) > 17500) {
                return back()
                    ->withErrors([
                        'amount' => 'Total food bill amount for this school cannot exceed ₹17,500.'
                    ])
                    ->withInput();
            }
        }

        // Block editing if already approved
        if ($bill->status === 'Approved') {
            return back()->with('error', 'Approved bills cannot be edited.');
        }

        $finalBillType = $request->bill_type;

        if ($request->bill_type === 'Misc' && $request->filled('custom_bill_type')) {
            $finalBillType = $request->custom_bill_type;
        }

        $limits = [
            'Inauguration' => 1200,
            'Generator'    => 5000,
        ];

        $existingTotal = CampExpenseBill::where('school_id', $bill->school_id)
            ->where('bill_type', $finalBillType)
            ->where('training_date', $bill->training_date)
            ->where('id', '!=', $bill->id) // exclude current bill
            ->sum('amount');

        $newTotal = $existingTotal + $request->amount;

        if (isset($limits[$finalBillType]) && $newTotal > $limits[$finalBillType]) {
            return back()
                ->withErrors([
                    'amount' => "{$finalBillType} total amount cannot exceed ₹{$limits[$finalBillType]}. Already used ₹{$existingTotal}."
                ])
                ->withInput();
        }

        if ($request->hasFile('bill_file')) {
            $school = School::find($bill->school_id);
            $schoolName = preg_replace('/[^A-Za-z0-9_\-]/', ' ', $school->scm_name);
            $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $school->scm_dist);

            $file = $request->file('bill_file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $folder = "EmergingTech/{$districtName}/{$schoolName}/Camp_Expenses/{$bill->bill_type}";
            $upload = $this->oneDrive->uploadDirect($file, $folder, $fileName);

            $bill->bill_path = $upload['path'] ?? $bill->bill_path;
            $bill->bill_url  = $upload['url'] ?? $bill->bill_url;
        }
        $bill->training_date = $request->training_date;
        $bill->amount = $request->amount;

        if ($bill->status === 'Rejected') {
            $bill->status = 'Pending';
            $bill->remarks = null;
            $bill->status_updated_at = null;
            $bill->status_updated_by = null;
        }

        $bill->save();

        return back()->with('success', 'Camp expense bill updated successfully!');
    }
    public function campexpenseDelete($id)
    {
        $bill = CampExpenseBill::findOrFail($id);

        // delete bill file from OneDrive if exists
        $existingBill = $bill->bill_path;
        if (is_array($existingBill)) {
            $existingBill = $existingBill[0] ?? null;
        }
        if (!empty($existingBill)) {
            try {
                $this->oneDrive->deleteFile($existingBill);
            } catch (\Exception $e) {
                Log::warning("Failed to delete camp expense bill file: " . $e->getMessage());
            }
        }
        $bill->delete();
        return back()->with('success', 'Camp expense bill deleted successfully!');
    }

    public function trainerTravels()
    {
        $districtId = auth::user()->district_id;

        $allowedSpecs = ['AI', 'IoT & Robotics'];
        // Get unique specializations from trainers in that district
        $specializations = Trainer::where('dist_id', $districtId)
            ->pluck('specialization')
            ->flatMap(function ($item) {

                // If already an array → return it directly
                if (is_array($item)) {
                    return $item;
                }

                // If stored as JSON string → decode it
                $decoded = json_decode($item, true);

                // If invalid JSON → skip
                return is_array($decoded) ? $decoded : [];
            })
            ->filter(function ($value) use ($allowedSpecs) {
                return in_array($value, $allowedSpecs);
            })
            ->unique()
            ->values();


        return view('travels.trainertravel', compact('specializations', 'districtId'));
    }
    public function getTrainersBySpecialization($districtId, $specialization)
    {
        $trainers = Trainer::where('dist_id', $districtId)
            ->whereJsonContains('specialization', $specialization)
            ->select('trainer_id', 'trainer_name')
            ->get();

        return response()->json($trainers);
    }

    public function trainerTravelStore(Request $request)
    {
        $request->validate([
            'trainer_id'        => 'required|exists:trainers,trainer_id',
            'district_id'       => 'required',
            'specialization'    => 'required',

            // Main travel
            'main_from'         => 'required|string|max:255',
            'main_to'           => 'required|string|max:255',
            'main_date'         => 'required|date',
            'main_mode'         => 'required|string|max:255',
            'main_amount'       => 'required|numeric|min:1',
            'main_bill'         => 'nullable|file|mimes:pdf|max:3072',

            // Return optional
            'return_bill_file'  => 'nullable|file|mimes:pdf|max:3072',

            'training_date' => 'nullable|date',
        ], [
            'main_bill.max' => 'Main bill file must be less than 3MB.',
            'return_bill_file.max' => 'Return bill file must be less than 3MB.',
        ]);

        $userId   = Auth::id();
        $trainer = Trainer::find($request->trainer_id);
        $trainerName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $trainer->trainer_name);
        $district = District::find($request->district_id);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $district->DSM_DSNM); // sanitize folder name


        // Upload Main Bill
        $mainBillUrl = null;
        $mainBillPath = null;
        if ($request->hasFile('main_bill')) {
            $mainBillFile = $request->file('main_bill');
            $mainFileName = time() . '_' . $mainBillFile->getClientOriginalName();

            $mainFolder = "Training_travel/{$districtName}/Trainer_{$trainerName}/travel_bills/main";

            $uploadMain = $this->oneDrive->uploadDirect($mainBillFile, $mainFolder, $mainFileName);

            $mainBillUrl = $uploadMain['url'] ?? null;
            $mainBillPath = $uploadMain['path'] ?? null;
        }

        // Upload Return Bill if present
        $returnBillUrl = null;
        $returnBillPath = null;
        if ($request->hasFile('return_bill_file')) {
            $returnBillFile = $request->file('return_bill_file');
            $returnFileName = time() . '_' . $returnBillFile->getClientOriginalName();

            $returnFolder = "Training_travel/{$districtName}/Trainer_{$trainerName}/travel_bills/return";

            $uploadReturn = $this->oneDrive->uploadDirect($returnBillFile, $returnFolder, $returnFileName);

            $returnBillUrl = $uploadReturn['url'] ?? null;
            $returnBillPath = $uploadReturn['path'] ?? null;
        }
        // Save to DB
        TrainerTravelBill::create([
            'trainer_id'        => $request->trainer_id,
            'district_id'       => $request->district_id,
            'specialization'    => $request->specialization,

            // Main
            'main_from'         => $request->main_from,
            'main_to'           => $request->main_to,
            'main_date'         => $request->main_date,
            'main_mode'         => $request->main_mode,
            'main_amount'       => $request->main_amount,
            'main_bill'         => $mainBillPath,
            'main_bill_url'     => $mainBillUrl,

            // Return
            'has_return'        => $request->has_return ?? false,
            'return_from'       => $request->return_from,
            'return_to'         => $request->return_to,
            'return_mode'       => $request->return_mode,
            'return_amount'     => $request->return_amount,
            'return_bill_file'  => $returnBillPath,
            'return_bill_url'   => $returnBillUrl,

            'uploaded_by'       => $userId,

            'training_date' => $request->training_date
        ]);

        return back()->with('success', 'Trainer travel bill submitted successfully!');
    }
    public function trainerTravelList(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->name;

        // Load districts for filter (Only for Accounts)
        $districts = District::orderBy('DSM_DSNM')->get();

        if (in_array($role, ['Accounts', 'OKCL'])) {

            $districtId = $request->district_id;   // can be null → all districts
            $status = $request->status;

            $records = TrainerTravelBill::with(['trainer', 'district'])
                ->join('dst_mst01', 'dst_mst01.DSM_DSCD', '=', 'trainer_travel_expenses.district_id')
                ->join('trainers', 'trainers.trainer_id', '=', 'trainer_travel_expenses.trainer_id')
                ->when($districtId, function ($q) use ($districtId) {
                    return $q->where('district_id', $districtId);
                })
                ->when($status, function ($q) use ($status) {
                    return $q->where('status', $status);
                })
                ->orderBy('dst_mst01.DSM_DSNM', 'ASC')
                ->orderBy('trainers.trainer_name', 'ASC')
                ->select('trainer_travel_expenses.*')
                ->get();

            return view('travels.trainertravellist', [
                'records' => $records,
                'districts' => $districts,
                'selectedDistrict' => $districtId,
            ]);
        }

        // Get logged-in trainer record if exists
        $trainer = Trainer::where('user_id', $user->id)->first();

        if ($role === 'Trainer' && $trainer) {
            // TRAINER — SEE ONLY OWN RECORDS
            $records = TrainerTravelBill::with(['trainer', 'district'])
                ->where('trainer_id', $trainer->trainer_id)
                ->latest()
                ->get();
        } elseif ($role === 'DLC') {

            $districtId = $request->district_id;
            $status = $request->status;
            // DLC — SEE RECORDS OF THEIR DISTRICT
            $records = TrainerTravelBill::with(['trainer', 'district'])
                ->where('district_id', $user->district_id)
                ->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->latest()
                ->get();
        } else {
            // NO ACCESS
            return back()->with('error', "You don't have permission to view this page.");
        }

        return view('travels.trainertravellist', [
            'records' => $records,
            'selectedDistrict' => $districtId
        ]);
    }
    public function trainerTravelUpdate(Request $request, $id)
    {
        $bill = TrainerTravelBill::findOrFail($id);

        $request->validate([
            'main_from' => 'required|string|max:255',
            'main_date' => 'required|date',
            'main_mode' => 'required|string|max:255',
            'main_amount' => 'required|numeric|min:1',
            'main_bill' => 'nullable|file|mimes:pdf|max:3072',
            'return_to' => 'nullable|string|max:255',
            'return_mode' => 'nullable|string|max:255',
            'return_amount' => 'nullable|numeric|min:0',
            'return_bill_file' => 'nullable|file|mimes:pdf|max:3072',
        ]);

        $trainerName  = preg_replace('/[^A-Za-z0-9_\-]/', '_', $bill->trainer->trainer_name);
        $districtName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $bill->district->DSM_DSNM);

        // Upload Main Bill if new one uploaded
        if ($request->hasFile('main_bill')) {

            // delete previous main bill if exists
            $existingMain = $bill->main_bill;
            if (is_array($existingMain)) {
                $existingMain = $existingMain[0] ?? null;
            }

            if (!empty($existingMain)) {
                try {
                    $this->oneDrive->deleteFile($existingMain);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old main travel bill: " . $e->getMessage());
                }
            }

            $mainBillFile = $request->file('main_bill');
            $mainFileName = time() . '_' . $mainBillFile->getClientOriginalName();

            $mainFolder = "Training_travel/{$districtName}/Trainer_{$trainerName}/travel_bills/main";

            $uploadMain = $this->oneDrive->uploadDirect($mainBillFile, $mainFolder, $mainFileName);

            $bill->main_bill = $uploadMain['path'] ?? $bill->main_bill;
            $bill->main_bill_url = $uploadMain['url'] ?? $bill->main_bill_url;
        }

        // Upload Return Bill if uploaded
        if ($request->hasFile('return_bill_file')) {

            $existingReturn = $bill->return_bill_file;
            if (is_array($existingReturn)) {
                $existingReturn = $existingReturn[0] ?? null;
            }

            if (!empty($existingReturn)) {
                try {
                    $this->oneDrive->deleteFile($existingReturn);
                } catch (\Exception $e) {
                    Log::warning("Failed to delete old return travel bill: " . $e->getMessage());
                }
            }

            $returnBillFile = $request->file('return_bill_file');
            $returnFileName = time() . '_' . $returnBillFile->getClientOriginalName();

            $returnFolder = "Training_travel/{$districtName}/Trainer_{$trainerName}/travel_bills/return";

            $uploadReturn = $this->oneDrive->uploadDirect($returnBillFile, $returnFolder, $returnFileName);

            $bill->return_bill_file = $uploadReturn['path'] ?? $bill->return_bill_file;
            $bill->return_bill_url  = $uploadReturn['url'] ?? $bill->return_bill_url;
        }

        // Update other fields
        $bill->main_from = $request->main_from;
        $bill->main_to   = $request->main_to;
        $bill->main_date = $request->main_date;
        $bill->main_mode = $request->main_mode;
        $bill->main_amount = $request->main_amount;

        $bill->has_return = $request->has_return ?? false;
        $bill->return_from = $request->return_from;
        $bill->return_to = $request->return_to;
        $bill->return_mode = $request->return_mode;
        $bill->return_amount = $request->return_amount;

        $bill->save();

        return back()->with('success', 'Travel bill updated successfully!');
    }
    public function updateTrainingDate(Request $request, $id)
    {
        $request->validate([
            'training_date' => 'required|date',
        ]);

        $bill = TrainerTravelBill::findOrFail($id);

        // Allow only Accounts user (role_id = 8)
        if (auth::user()->role_id != 8) {
            abort(403, 'Unauthorized action.');
        }

        $bill->training_date = $request->training_date;
        $bill->status_updated_by = auth::user()->id;
        $bill->status_updated_at = now();

        $bill->save();

        return back()->with('success', 'Training date updated successfully!');
    }

    public function previewFile(Request $request)
    {
        $path = $request->query('path');

        if (!$path) {
            return response('Invalid file path', 400);
        }

        // Cache OneDrive download URL for 5 minutes
        $downloadUrl = Cache::remember("onedrive_download_" . md5($path), 300, function () use ($path) {
            $fileInfo = $this->oneDrive->getFileInfo($path);
            return $fileInfo['@microsoft.graph.downloadUrl'] ?? null;
        });

        if (!$downloadUrl) {
            return response('File not found or access denied', 404);
        }

        // Fetch file headers from OneDrive
        $response = Http::head($downloadUrl);
        $contentType = $response->header('Content-Type', 'application/octet-stream');

        // ✅ Only allow PDF files
        if (!str_contains($contentType, 'pdf')) {
            return response('Only PDF preview is supported.', 415);
        }

        // Fetch the PDF content and stream inline
        $pdfContent = Http::get($downloadUrl)->body();

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="travel_bills.pdf"');
    }

    public function approve(Request $request, $id)
    {
        $bill = TrainerTravelBill::findOrFail($id);

        $bill->status = 'Approved';
        $bill->remarks = $request->remarks;
        $bill->status_updated_at = now();
        $bill->status_updated_by = Auth::id();
        $bill->save();

        return back()->with('success', 'Travel bill approved successfully!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'remarks' => 'required|string|max:500'
        ]);

        $bill = TrainerTravelBill::findOrFail($id);

        $bill->status = 'Rejected';
        $bill->remarks = $request->remarks;
        $bill->status_updated_at = now();
        $bill->status_updated_by = Auth::id();
        $bill->save();

        return back()->with('error', 'Travel bill rejected.');
    }
    public function revert($id)
    {
        $record = TrainerTravelBill::findOrFail($id);

        $record->status = 'Pending';
        $record->remarks = null;
        $record->status_updated_at = now();
        $record->status_updated_by = Auth::id();
        $record->save();

        return back()->with('success', 'Status reverted to Pending.');
    }
}
