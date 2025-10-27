<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\District;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class CoordinatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');


        if (in_array($roleId, [1, 2])) {
            // ✅ Role 1 or 2 can see ALL coordinators
            $coordinators = Coordinator::latest()->get();
        } else {
            // ✅ Others see only coordinators created by them (via assignUnder_id)
            $coordinators = Coordinator::whereHas('user', function ($query) use ($userId) {
                $query->where('assignUnder_id', $userId);
            })->latest()->get();
        }
        // $coordinators = Coordinator::latest()->get();
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        if ($roleId == 1 || $roleId == 2) {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        } else {
            $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        }

        return view('coordinatorlist', compact('coordinators', 'districts', 'schools'));
    }

    public function store(Request $request, Coordinator $coordinator)
    {

        $userId   = Auth::id();
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        // // Ensure storage/app/public exists
        // $storagePublicPath = storage_path('app/public');
        // if (!File::exists($storagePublicPath)) {
        //     File::makeDirectory($storagePublicPath, 0775, true);
        // }

        // // Ensure public/storage symlink exists
        // $publicStorage = public_path('storage');
        // if (!file_exists($publicStorage)) {
        //     Artisan::call('storage:link');
        // }

        $validated = $request->validate([
            'coordinator_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($coordinator) {
                    // Check trainers table excluding current trainer
                    $existsInTrainers = DB::table('coordinator_mst')
                        ->where('email', $value)
                        ->where('coordinator_id', '<>', $coordinator->coordinator_id)
                        ->exists();

                    // Check user table
                    $existsInUsers = DB::table('users')
                        ->where('email', $value)
                        ->exists();

                    if ($existsInTrainers || $existsInUsers) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
            'phone' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'school' => 'required',
            // 'dist_id' => 'nullable|string|max:50',
            // 'district' => 'nullable|string|max:500',
            'pincode' => 'required|digits:6',
            'highest_qualification' => 'required|string|max:255',
            'other_qualification' => 'nullable|string|max:255',
            'cv' => 'file|mimes:pdf,application/pdf,doc,docx|max:2048',
            'experience_certificate' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'image|mimes:jpg,jpeg,png|max:2048',
            'education_certificates.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhar_card' => 'file|mimes:pdf,application/pdf,doc,docx|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
            'experience_certificate.max' => 'The experience certificate must not be larger than 2 MB.',
            'photo.max' => 'The photo must not be larger than 2 MB.',
            'education_certificates.*.max' => 'Each education certificate must not be larger than 2 MB.',
            'aadhar_card.max' => 'The aadhaar card must not be larger than 2 MB.',
        ]);

        $data = $validated;

        $data['scm_id'] = $request->school;
        $data['dist_id'] = $districtID[0]->district_id;

        if ($request->highest_qualification === 'Other') {
            $data['highest_qual'] = $request->other_qualification; // save custom input
        } else {
            $data['highest_qual'] = $request->highest_qualification;
        }

        $coordinatorName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->coordinator_name);
        // File uploads
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['cv'] = $file->storeAs("coordinators/{$coordinatorName}/cv", $filename, 'public');
        }
        if ($request->hasFile('experience_certificate')) {
            $file = $request->file('experience_certificate');
            $filename = $file->getClientOriginalName();
            $data['experience_certificate'] = $file->storeAs("coordinators/{$coordinatorName}/experience", $filename, 'public');
        }
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $data['photo'] = $file->storeAs("coordinators/{$coordinatorName}/photos", $filename, 'public');
        }
        if ($request->hasFile('education_certificates')) {
            $paths = [];
            foreach ($request->file('education_certificates') as $file) {
                $filename = $file->getClientOriginalName();
                $paths[] = $file->storeAs("coordinators/{$coordinatorName}/education", $filename, 'public');
            }
            $data['education_certificates'] = $paths; // no json_encode, Eloquent will cast
        }
        if ($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['aadhar_card'] = $file->storeAs("coordinators/{$coordinatorName}/aadhar_card", $filename, 'public');
        }


        $user_data = ([
            'name' => $data['coordinator_name'],
            'email' => $data['email'],
            'district_id' => $data['dist_id'],
            'institute_id' => $data['scm_id'],
            'password' => Hash::make('Coordinator@ET'),
            'role_id' => 6,
            'assignUnder_id' => $userId,
            'created_at' => now()

        ]);
        $User_dtls = User::create($user_data);
        $data['user_id'] = $User_dtls->id;
        Coordinator::create($data);

        return redirect()->route('coordinators.index')->with('success', 'Coordinator added successfully!');
    }


    public function edit(Coordinator $coordinator)
    {
        return response()->json($coordinator); // for modal edit via AJAX
    }



    public function update(Request $request, Coordinator $coordinator)
    {
        // // Ensure storage/app/public exists
        // $storagePublicPath = storage_path('app/public');
        // if (!File::exists($storagePublicPath)) {
        //     File::makeDirectory($storagePublicPath, 0775, true);
        // }

        // // Ensure public/storage symlink exists
        // $publicStorage = public_path('storage');
        // if (!file_exists($publicStorage)) {
        //     Artisan::call('storage:link');
        // }

        $validated = $request->validate([
            'coordinator_name' => 'required|string|max:255',
            'email' => 'required|email|unique:coordinator_mst,email,' . $coordinator->coordinator_id . ',coordinator_id',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'school' => 'required',
            // 'district' => 'nullable|string|max:500',
            // 'dist_id' => 'nullable|string|max:50',
            'pincode' => 'nullable|digits:6',
            'highest_qualification' => 'required|string|max:255',
            'other_qualification' => 'nullable|string|max:255',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
            'experience_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'education_certificates.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhar_card' => 'nullable|file|mimes:pdf,application/pdf|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
            'experience_certificate.max' => 'The experience certificate must not be larger than 2 MB.',
            'photo.max' => 'The photo must not be larger than 2 MB.',
            'education_certificates.*.max' => 'Each education certificate must not be larger than 2 MB.',
            'aadhar_card.max' => 'The aadhaar card must not be larger than 2 MB.',
        ]);

        $data = $validated;

        if ($request->highest_qualification === 'Other') {
            $data['highest_qual'] = $request->other_qualification;
        } else {
            $data['highest_qual'] = $request->highest_qualification;
        }

        $coordinatorName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->coordinator_name);
        // Handle file uploads and deletions
        if ($request->hasFile('cv')) {
            if ($coordinator->cv && Storage::disk('public')->exists($coordinator->cv)) {
                Storage::disk('public')->delete($coordinator->cv);
            }
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['cv'] = $file->storeAs("coordinators/{$coordinatorName}/cv", $filename, 'public');
        }
        if ($request->hasFile('aadhar_card')) {
            if ($coordinator->aadhar_card && Storage::disk('public')->exists($coordinator->aadhar_card)) {
                Storage::disk('public')->delete($coordinator->aadhar_card);
            }
            $file = $request->file('aadhar_card');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['aadhar_card'] = $file->storeAs("coordinators/{$coordinatorName}/aadhar_card", $filename, 'public');
        }
        if ($request->hasFile('experience_certificate')) {
            if ($coordinator->experience_certificate  && Storage::disk('public')->exists($coordinator->experience_certificate)) {
                Storage::disk('public')->delete($coordinator->experience_certificate);
            }
            $file = $request->file('experience_certificate');
            $filename = $file->getClientOriginalName();
            $data['experience_certificate'] = $file->storeAs("coordinators/{$coordinatorName}/experience", $filename,  'public');
        }

        if ($request->hasFile('photo')) {
            if ($coordinator->photo && Storage::disk('public')->exists($coordinator->photo)) {
                Storage::disk('public')->delete($coordinator->photo);
            }
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $data['photo'] = $file->storeAs("coordinators/{$coordinatorName}/photos", $filename, 'public');
        }

        // Handle Education Certificates
        if ($request->hasFile('education_certificates')) {
            // Delete old files if any
            $oldFiles = [];
            if ($coordinator->education_certificates) {
                if (is_string($coordinator->education_certificates)) {
                    $oldFiles = json_decode($coordinator->education_certificates, true) ?? [];
                } elseif (is_array($coordinator->education_certificates)) {
                    $oldFiles = $coordinator->education_certificates;
                }
            }

            foreach ($oldFiles as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Store new files
            $paths = [];
            foreach ($request->file('education_certificates') as $file) {
                $filename = $file->getClientOriginalName();
                $paths[] = $file->storeAs("coordinators/{$coordinatorName}/education", $file->getClientOriginalName(), 'public');
            }
            $data['education_certificates'] = json_encode($paths);
        }
        $data['scm_id'] = $data['school'];
        $coordinator->update($data);
        $user_id = Coordinator::select('user_id')->where('coordinator_id', $coordinator->coordinator_id)->first()->user_id;
        $user = User::where('id', $user_id)->first();
        if ($user) {
            $user->update([
                'name' => $data['coordinator_name'],
                'email' => $data['email'],
                'institute_id' => $data['scm_id'],
            ]);
        }

        return redirect()->route('coordinators.index')->with('success', 'Coordinator updated successfully!');
    }

    public function destroy(Coordinator $coordinator)
    {
        try {
            // Build coordinator folder path (same logic as in store/update)
            $coordinatorName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $coordinator->coordinator_name);
            $folderPath = "coordinators/{$coordinatorName}";

            // Delete entire folder
            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);
            }

            // Delete coordinator record
            $coordinator->delete();

            return redirect()->route('coordinators.index')
                ->with('success', 'Coordinator deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('coordinators.index')
                ->with('error', 'Error deleting coordinator: ' . $e->getMessage());
        }
    }
}
