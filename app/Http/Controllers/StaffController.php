<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\School;
use App\Models\SuppStaff;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $userId   = Auth::id();
        $districtID= User::select('district_id')->where('id', $userId )->get('district_id');
        $roleId = Auth::user()->role_id;

        if (in_array($roleId, [1, 2])) {
            // ✅ Role 1 or 2 can see ALL staff
            $suppstaffs = SuppStaff::latest()->get();
        } else {
            // ✅ Others see only staff created by them (via assignUnder_id)
            $suppstaffs = SuppStaff::whereHas('user', function ($query) use ($userId) {
                $query->where('assignUnder_id', $userId);
            })->latest()->get();
        }
        
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        $schools = School::select('scm_id', 'scm_name')->where('scm_dist_id',$districtID[0]->district_id)->orderBy('scm_name', 'asc')->get();

        return view('supportingstafflist', compact('schools', 'districts', 'suppstaffs'));
    }

     public function store(Request $request, SuppStaff $suppstaff)
    {

        $userId   = Auth::id();
        $districtID= User::select('district_id')->where('id', $userId )->get('district_id');

        $validated = $request->validate([
            'ss_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($suppstaff) {
                    // Check trainers table excluding current trainer
                    $existsInTrainers = DB::table('support_staff_mst')
                        ->where('email', $value)
                        ->where('ss_id', '<>', $suppstaff->ss_id)
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
            'cv' => 'file|mimes:pdf,application/pdf|max:2048',
            'photo' => 'image|mimes:jpg,jpeg,png|max:2048',
            'education_certificates.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhar_card' => 'file|mimes:pdf,application/pdf|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
            'photo.max' => 'The photo must not be larger than 2 MB.',
            'education_certificates.*.max' => 'Each education certificate must not be larger than 2 MB.',
            'aadhar_card.max' => 'The aadhaar card must not be larger than 2 MB.',
        ]);

        $data = $validated;

        $data['scm_id'] = $request->school;
        $data['dist_id'] = $districtID[0]->district_id;
        
        if ($request->highest_qualification === 'Other') {
            $data['highest_qual'] = $request->other_qualification; // save custom input
        }else {
            $data['highest_qual'] = $request->highest_qualification;
        }

        $staffName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->ss_name);
        // File uploads
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['cv'] = $file->storeAs("supportingstaff/{$staffName}/cv", $filename, 'public');
        }
        if ($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['aadhar_card'] = $file->storeAs("supportingstaff/{$staffName}/aadhar_card", $filename, 'public');
        }
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $data['photo'] = $file->storeAs("supportingstaff/{$staffName}/photos", $filename, 'public');
        }
        if ($request->hasFile('education_certificates')) {
            $paths = [];
            foreach ($request->file('education_certificates') as $file) {
                $filename = $file->getClientOriginalName();
                $paths[] = $file->storeAs("supportingstaff/{$staffName}/education", $filename, 'public');
            }
            $data['education_certificates'] = $paths; // no json_encode, Eloquent will cast
        }

        
        $user_data=([
            'name' => $data['ss_name'],
            'email' => $data['email'],
            'district_id' => $data['dist_id'],
            'institute_id' => $data['scm_id'],
            'password' => Hash::make('Staff@ET'),
            'role_id'=>7,
            'assignUnder_id'=>$userId,
            'created_at'=>now()
            
        ]);
        $User_dtls=User::create($user_data);
        $data['user_id'] = $User_dtls->id;
        SuppStaff::create($data);
        
        return redirect()->route('supstaff.index')->with('success', 'Supporting Staff added successfully.');
    }

    public function edit(SuppStaff $supstaff)
    {
        return response()->json($supstaff); // for modal edit via AJAX
    }
        
    public function update(Request $request, SuppStaff $supstaff )
    {
        
        
        $validated = $request->validate([
            'ss_name' => 'required|string|max:255',
            'email' => 'required|email|unique:support_staff_mst,email,' . $supstaff->ss_id . ',ss_id',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'school' => 'required',
            // 'district' => 'nullable|string|max:500',
            // 'dist_id' => 'nullable|string|max:50',
            'pincode' => 'nullable|digits:6',
            'highest_qualification' => 'required|string|max:255',
            'other_qualification' => 'nullable|string|max:255',
            'cv' => 'nullable|file|mimes:pdf,application/pdf|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'education_certificates.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhar_card' => 'nullable|file|mimes:pdf,application/pdf|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
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

        $supportingstaffName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->ss_name);
        // Handle file uploads and deletions
        if ($request->hasFile('cv')) {
            if ($supstaff->cv && Storage::disk('public')->exists($supstaff->cv)) {
                Storage::disk('public')->delete($supstaff->cv);
            }
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['cv'] = $file->storeAs("supportingstaff/{$supportingstaffName}/cv", $filename, 'public');
        }
        
        if ($request->hasFile('aadhar_card')) {
            if ($supstaff->aadhar_card && Storage::disk('public')->exists($supstaff->aadhar_card)) {
                Storage::disk('public')->delete($supstaff->aadhar_card);
            }
            $file = $request->file('aadhar_card');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['aadhar_card'] = $file->storeAs("supportingstaff/{$supportingstaffName}/aadhar_card", $filename, 'public');
        }
        
        if ($request->hasFile('photo')) {
            if ($supstaff->photo && Storage::disk('public')->exists($supstaff->photo)) {
                Storage::disk('public')->delete($supstaff->photo);
            }
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $data['photo'] = $file->storeAs("supportingstaff/{$supportingstaffName}/photos", $filename, 'public');
        }

        // Handle Education Certificates
        if ($request->hasFile('education_certificates')) {
            // Delete old files if any
            $oldFiles = [];
            if ($supstaff->education_certificates) {
                if (is_string($supstaff->education_certificates)) {
                    $oldFiles = json_decode($supstaff->education_certificates, true) ?? [];
                } elseif (is_array($supstaff->education_certificates)) {
                    $oldFiles = $supstaff->education_certificates;
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
                $paths[] = $file->storeAs("supportingstaff/{$supportingstaffName}/education", $file->getClientOriginalName(), 'public');
            }
            $data['education_certificates'] = json_encode($paths);
        }
        // if ($request->hasFile('aadhar_card')) {
        //     // Delete old files if any
        //     $oldFiles = [];
        //     if ($request->hasFile('aadhar_card')) {
        //         if ($supstaff->aadhar_card && Storage::disk('public')->exists($supstaff->aadhar_card)) {
        //             Storage::disk('public')->delete($supstaff->aadhar_card);
        //         }
        //         $file = $request->file('aadhar_card');
        //         $filename = $file->getClientOriginalName(); // to avoid overwriting
        //         $data['aadhar_card'] = $file->storeAs("supportingstaff/{$supportingstaffName}/aadhar_card", $filename, 'public');
        //     }

        //     foreach ($oldFiles as $path) {
        //         if (Storage::disk('public')->exists($path)) {
        //             Storage::disk('public')->delete($path);
        //         }
        //     }

        //     // Store new files
        //     $paths = [];
        //     foreach ($request->file('aadhar_card') as $file) {
        //         $filename = $file->getClientOriginalName();
        //         $paths[] = $file->storeAs("supportingstaff/{$supportingstaffName}/aadhar-card", $file->getClientOriginalName(), 'public');
        //     }
        //     $data['education_certificates'] = json_encode($paths);
        // }
        $data['scm_id']=$data['school'];
        $supstaff->update($data);
        
        $user_id = SuppStaff::select('user_id')->where('ss_id', $supstaff->ss_id)->first()->user_id;

        $user = User::where('id', $user_id)->first();
        
        if ($user) {
            $user->update([
                'name' => $data['ss_name'],
                'email' => $data['email'],
                'institute_id' => $data['scm_id'],
            ]);
        }

        return redirect()->route('supstaff.index')->with('success', 'Supporting Staff updated successfully!');
    }

    public function destroy(SuppStaff $supstaff)
    {
        try {
            // Build Supporting Staff folder path (same logic as in store/update)
            $supportingstaffName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $supstaff->ss_name);
            $folderPath = "supportingstaff/{$supportingstaffName}";

            // Delete entire folder
            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);
            }

            // Delete Supporting Staff record
            $supstaff->delete();

            return redirect()->route('supstaff.index')
                ->with('success', 'Supporting Staff deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('supstaff.index')
                ->with('error', 'Error deleting Supporting Staff: ' . $e->getMessage());
        }
    }
}
