<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Trainer;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::latest()->get();
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->orderBy('DSM_DSNM', 'asc')->get();
        return view('trainerlist', compact('trainers', 'districts'));
    }

    /**
     * Store a newly created trainer in storage.
     */
    public function store(Request $request)
    {
        // dd( $request->all());

        $validated = $request->validate([
            'trainer_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'specialization' => 'required|array',
            'specialization.*' => 'string|in:AI,IoT & Robotics,Cybersecurity',
            'address' => 'nullable|string|max:500',
            'dist_id' => 'nullable|string|max:50',
            'district' => 'nullable|string|max:500',
            'pincode' => 'nullable|digits:6',
            'cv' => 'nullable|file|mimes:pdf,application/pdf,doc,docx|max:2048',
            'experience_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'education_certificates.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'aadhar_card' => 'nullable|file|mimes:pdf,application/pdf,doc,docx|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
            'experience_certificate.max' => 'The experience certificate must not be larger than 2 MB.',
            'photo.max' => 'The photo must not be larger than 2 MB.',
            'education_certificates.*.max' => 'Each education certificate must not be larger than 2 MB.',
            'aadhar_card.max' => 'The aadhaar card must not be larger than 2 MB.',
        ]);
            
        $data = $validated;

    // Always save specialization as array (let Eloquent cast to JSON)
    $data['specialization'] = $request->input('specialization', []);

    $trainerName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->trainer_name);
    // File uploads
    if ($request->hasFile('cv')) {
        $file = $request->file('cv');
        $filename = $file->getClientOriginalName(); // to avoid overwriting
        $data['cv'] = $file->storeAs("trainers/{$trainerName}/cv",$filename, 'public');
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
    if ($request->hasFile('aadhar_card')) {
            $file = $request->file('aadhar_card');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['aadhar_card'] = $file->storeAs("trainers/{$trainerName}/aadhar_card", $filename, 'public');
        }


        Trainer::create($data);

        return redirect()->route('trainers.index')->with('success', 'Trainer added successfully!');
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
        'district' => 'nullable|string|max:500',
        'dist_id' => 'nullable|string|max:50',
        'pincode' => 'nullable|digits:6',
        'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'experience_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'education_certificates.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'aadhar_card' => 'nullable|file|mimes:pdf,application/pdf,doc,docx|max:2048',
        ], [
            'cv.max' => 'The CV must not be larger than 2 MB.',
            'experience_certificate.max' => 'The experience certificate must not be larger than 2 MB.',
            'photo.max' => 'The photo must not be larger than 2 MB.',
            'education_certificates.*.max' => 'Each education certificate must not be larger than 2 MB.',
            'aadhar_card.max' => 'The aadhaar card must not be larger than 2 MB.',
    
    ]);

    $data = $validated;

    $data['specialization'] = $request->input('specialization', []);
    $trainerName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->trainer_name);
        // Handle file uploads and deletions
    if ($request->hasFile('cv')) {
        if ($trainer->cv && Storage::disk('public')->exists($trainer->cv))  {
            Storage::disk('public')->delete($trainer->cv);
        }
            $file = $request->file('cv');
            $filename = $file->getClientOriginalName(); // to avoid overwriting
            $data['cv'] = $file->storeAs("trainers/{$trainerName}/cv",$filename, 'public');
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
     if ($request->hasFile('aadhar_card')) {
                if ($trainer->aadhar_card && Storage::disk('public')->exists($trainer->aadhar_card)) {
                    Storage::disk('public')->delete($trainer->aadhar_card);
                }
                $file = $request->file('aadhar_card');
                $filename = $file->getClientOriginalName(); // to avoid overwriting
                $data['aadhar_card'] = $file->storeAs("trainers/{$trainerName}/aadhar_card", $filename, 'public');
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

        $trainer->update($data);

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
                         ->with('success', 'Trainer and all related files deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('trainers.index')
                         ->with('error', 'Error deleting trainer: ' . $e->getMessage());
    }
}

}
