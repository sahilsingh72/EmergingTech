<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoordinatorController extends Controller
{
    public function index()
    {
        // $coordinators = Coordinator::latest()->paginate(10); // paginated
        $coordinators = Coordinator::latest()->get();
        $districts = District::select('DSM_DSCD', 'DSM_DSNM')->get();

        return view('coordinatorlist', compact('coordinators', 'districts'));
        // return view('coordinatorlist');
    }

    public function store(Request $request)
    {
        // dd( $request->all());

        $validated = $request->validate([
            'coordinator_name' => 'required|string|max:255',
            'email' => 'required|email|unique:coordinator_mst,email',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
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


        Coordinator::create($data);

        return redirect()->route('coordinators.index')->with('success', 'Coordinator added successfully!');
    }


    public function edit(Coordinator $coordinator)
    {
        return response()->json($coordinator); // for modal edit via AJAX
    }



    public function update(Request $request, Coordinator $coordinator)
    {
        $validated = $request->validate([
            'coordinator_name' => 'required|string|max:255',
            'email' => 'required|email|unique:coordinator_mst,email,' . $coordinator->coordinator_id . ',coordinator_id',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
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
            if ($request->hasFile('aadhar_card')) {
                if ($coordinator->aadhar_card && Storage::disk('public')->exists($coordinator->aadhar_card)) {
                    Storage::disk('public')->delete($coordinator->aadhar_card);
                }
                $file = $request->file('aadhar_card');
                $filename = $file->getClientOriginalName(); // to avoid overwriting
                $data['aadhar_card'] = $file->storeAs("coordinators/{$coordinatorName}/aadhar_card", $filename, 'public');
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

        $coordinator->update($data);

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
