<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\School;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $userId = $user->id;
        $roleId = $user->role_id;

        // $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        $districtId = $user->district_id;

        $trainerQuery = Trainer::query()
            ->with([
                'schools' => fn ($query) => $query->forBatch(),
                'district',
            ])
            ->whereHas('schools', function ($query) {
                $query->forBatch();
            });

        if (in_array($roleId, [1, 2])) {
            // $trainers = Trainer::with('schools', 'district')
            $trainers = $trainerQuery
                ->orderBy(District::select('DSM_DSNM')
                    ->whereColumn('dst_mst01.DSM_DSCD', 'trainers.dist_id'))
                ->get();
        } elseif ($roleId == 6) {
            $dlcId = Auth::user()->assignUnder_id;

            // $trainers = Trainer::with('schools', 'district')->whereHas('user', function ($query) use ($dlcId) {
            $trainers = $trainerQuery->whereHas('user', function ($query) use ($dlcId) {
                $query->where('assignUnder_id', $dlcId);
            })->latest()->get();
        } else {
            // $trainers = Trainer::with('schools', 'district')->whereHas('user', function ($query) use ($userId) {
            $trainers = $trainerQuery->whereHas('user', function ($query) use ($userId) {
                $query->where('assignUnder_id', $userId);
            })->latest()->get();
        }

        foreach ($trainers as $trainer) {
            $trainer->school_ids = $trainer->schools->pluck('scm_id')->toArray();
        }

        $districts = District::select('DSM_DSCD', 'DSM_DSNM')
            ->whereHas('schools', function ($query) {
                $query->forBatch();
            })
            ->orderBy('DSM_DSNM')
            ->get();

        // $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        // if ($roleId == 1 || $roleId == 2) {
        //     $schools = School::select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->orderBy('scm_dist', 'asc')->get();
        // } else {
        //     $schools = School::forBatch()->select('scm_id', 'scm_name', 'scm_udise_code', 'scm_dist')->where('scm_dist_id', $districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();
        // }

        $schools = School::forBatch()
            ->select(
                'scm_id',
                'scm_name',
                'scm_udise_code',
                'scm_dist',
                'scm_dist_id'
            )
            ->when(
                !in_array($roleId, [1, 2]),
                fn ($query) => $query->where('scm_dist_id', $districtId)
            )
            ->orderBy(
                in_array($roleId, [1, 2]) ? 'scm_dist' : 'scm_name'
            )
            ->get();

        return view('trainerlist', compact('trainers', 'districts', 'schools'));
    }


    public function store(Request $request, Trainer $trainer)
    {
        $userId   = Auth::id();
        $districtID = User::select('district_id')->where('id', $userId)->get('district_id');

        $validated = $request->validate([
            'trainer_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($trainer) {
                    // Check trainers table excluding current trainer
                    $existsInTrainers = DB::table('trainers')
                        ->where('email', $value)
                        ->where('trainer_id', '<>', $trainer->trainer_id)
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
            'specialization' => 'required|array',
            'specialization.*' => 'string|in:AI,IoT & Robotics,Cybersecurity',
            'address' => 'required|string|max:500',
            'school' => 'required|array',
            'school.*' => 'exists:school_mst,scm_id',

            // 'dist_id' => 'nullable|string|max:50',
            // 'district' => 'nullable|string|max:500',
            'pincode' => 'required|digits:6',
            'highest_qualification' => 'required|string|max:255',
            'other_qualification' => 'nullable|string|max:255',
            'cv' => 'file|mimes:pdf,application/pdf|max:2048',
            'experience_certificate' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'image|mimes:jpg,jpeg,png|max:2048',
            'education_certificates.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhar_card' => 'file|mimes:pdf,application/pdf|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
            'experience_certificate.max' => 'The experience certificate must not be larger than 2 MB.',
            'photo.max' => 'The photo must not be larger than 2 MB.',
            'education_certificates.*.max' => 'Each education certificate must not be larger than 2 MB.',
            'aadhar_card.max' => 'The aadhaar card must not be larger than 2 MB.',
        ]);

        //  RUN EVERYTHING INSIDE A TRANSACTION
        DB::beginTransaction();
        try {

            $data = $validated;

            //$data['scm_id'] = $request->school;
            $data['dist_id'] = $districtID[0]->district_id;

            if ($request->highest_qualification === 'Other') {
                $data['highest_qual'] = $request->other_qualification; // save custom input
            } else {
                $data['highest_qual'] = $request->highest_qualification;
            }

            // Always save specialization as array (let Eloquent cast to JSON)
            $data['specialization'] = $request->input('specialization', []);

            $trainerName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->trainer_name);
            // File uploads
            if ($request->hasFile('cv')) {
                $file = $request->file('cv');
                $filename = $file->getClientOriginalName(); // to avoid overwriting
                $data['cv'] = $file->storeAs("trainers/{$trainerName}/cv", $filename, 'public');
            }
            if ($request->hasFile('aadhar_card')) {
                $file = $request->file('aadhar_card');
                $filename = $file->getClientOriginalName(); // to avoid overwriting
                $data['aadhar_card'] = $file->storeAs("trainers/{$trainerName}/aadhar_card", $filename, 'public');
            }
            if ($request->hasFile('experience_certificate')) {
                $file = $request->file('experience_certificate');
                $filename = $file->getClientOriginalName();
                $data['experience_certificate'] = $file->storeAs("trainers/{$trainerName}/experience", $filename, 'public');
            }
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = $file->getClientOriginalName();
                $data['photo'] = $file->storeAs("trainers/{$trainerName}/photos", $filename, 'public');
            }
            if ($request->hasFile('education_certificates')) {
                $paths = [];
                foreach ($request->file('education_certificates') as $file) {
                    $filename = $file->getClientOriginalName();
                    $paths[] = $file->storeAs("trainers/{$trainerName}/education", $filename, 'public');
                }
                $data['education_certificates'] = $paths; // no json_encode, Eloquent will cast
            }
            $user_data = ([
                'name' => $data['trainer_name'],
                'email' => $data['email'],
                'district_id' => $data['dist_id'],
                //'institute_id' => $data['scm_id'],
                'password' => Hash::make('Trainer@ET'),
                'role_id' => 5,
                'assignUnder_id' => $userId,
                'created_at' => now()
            ]);
            $User_dtls = User::create($user_data);
            $data['user_id'] = $User_dtls->id;

            $trainer = Trainer::create($data);
            // ✅ Store school mappings
            foreach ($request->school as $scm_id) {
                DB::table('trainer_scm_allocation')->insert([
                    'trainer_id' => $trainer->trainer_id,
                    'scm_id' => $scm_id,
                    'created_at' => now(),
                    'update_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('trainers.index')->with('success', 'Trainer added successfully!');
        } catch (\Exception $e) {

            DB::rollBack(); // ⭐ ROLLBACK USER + COORDINATOR

            return back()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function edit(Trainer $trainer)
    {
        return response()->json($trainer); // for modal edit via AJAX
    }



    public function update(Request $request, Trainer $trainer)
    {

        $validated = $request->validate([
            'trainer_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $trainer->trainer_id . ',trainer_id',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'specialization' => 'required|array',
            'specialization.*' => 'string|in:AI,IoT & Robotics,Cybersecurity',
            'address' => 'nullable|string|max:500',
            'school' => 'required|array',
            'school.*' => 'exists:school_mst,scm_id',
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

        $data['specialization'] = $request->input('specialization', []);
        $trainerName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->trainer_name);
        // Handle file uploads and deletions
        if ($request->hasFile('cv')) {
            if ($trainer->cv && Storage::disk('public')->exists($trainer->cv)) {
                Storage::disk('public')->delete($trainer->cv);
            }
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['cv'] = $file->storeAs("trainers/{$trainerName}/cv", $filename, 'public');
        }
        if ($request->hasFile('aadhar_card')) {
            if ($trainer->aadhar_card && Storage::disk('public')->exists($trainer->aadhar_card)) {
                Storage::disk('public')->delete($trainer->aadhar_card);
            }
            $file = $request->file('aadhar_card');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['aadhar_card'] = $file->storeAs("trainers/{$trainerName}/aadhar_card", $filename, 'public');
        }
        if ($request->hasFile('experience_certificate')) {
            if ($trainer->experience_certificate  && Storage::disk('public')->exists($trainer->experience_certificate)) {
                Storage::disk('public')->delete($trainer->experience_certificate);
            }
            $file = $request->file('experience_certificate');
            $filename = $file->getClientOriginalName();
            $data['experience_certificate'] = $file->storeAs("trainers/{$trainerName}/experience", $filename,  'public');
        }

        if ($request->hasFile('photo')) {
            if ($trainer->photo && Storage::disk('public')->exists($trainer->photo)) {
                Storage::disk('public')->delete($trainer->photo);
            }
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $data['photo'] = $file->storeAs("trainers/{$trainerName}/photos", $filename, 'public');
        }

        // Handle Education Certificates
        if ($request->hasFile('education_certificates')) {
            // Delete old files if any
            $oldFiles = [];
            if ($trainer->education_certificates) {
                if (is_string($trainer->education_certificates)) {
                    $oldFiles = json_decode($trainer->education_certificates, true) ?? [];
                } elseif (is_array($trainer->education_certificates)) {
                    $oldFiles = $trainer->education_certificates;
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
                $paths[] = $file->storeAs("trainers/{$trainerName}/education", $file->getClientOriginalName(), 'public');
            }
            $data['education_certificates'] = json_encode($paths);
        }
        // $data['scm_id']=$data['school'];
        $trainer->update($data);
        $user_id = Trainer::select('user_id')->where('trainer_id', $trainer->trainer_id)->first()->user_id;

        $user = User::where('id', $user_id)->first();
        if ($user) {
            $user->update([
                'name' => $data['trainer_name'],
                'email' => $data['email'],
                // 'institute_id' => $data['scm_id'],
            ]);
        }
        DB::table('trainer_scm_allocation')
            ->where('trainer_id', $trainer->trainer_id)
            ->delete();

        foreach ($request->school as $scm_id) {
            DB::table('trainer_scm_allocation')->insert([
                'trainer_id' => $trainer->trainer_id,
                'scm_id' => $scm_id,
                'created_at' => now(),
                'update_at' => now(),
            ]);
        }

        return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully!');
    }

    /**
     * Remove the specified trainer from storage.
     */
    public function destroy(Trainer $trainer)
    {
        try {
            // Build trainer folder path (same logic as in store/update)
            $trainerName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $trainer->trainer_name);
            $folderPath = "trainers/{$trainerName}";

            // Delete entire folder
            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);
            }

            // Delete trainer record
            $trainer->delete();

            return redirect()->route('trainers.index')
                ->with('success', "Trainer {$trainer->trainer_name} deleted successfully!");
        } catch (\Exception $e) {
            return redirect()->route('trainers.index')
                ->with('error', 'Error deleting trainer: ' . $e->getMessage());
        }
    }
}
