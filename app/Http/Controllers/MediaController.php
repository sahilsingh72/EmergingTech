<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OneDriveService;

class MediaController extends Controller
{
    protected $oneDrive;

    public function __construct(OneDriveService $oneDrive)
    {
        $this->oneDrive = $oneDrive;
    }

    public function socialMedia(Request $request){
        
        $tab = $request->get('tab', 'instagram');

        $posts = [
            'instagram' => SocialMedia::where('media_type', 'instagram')
                ->latest()
                ->paginate(8, ['*'], 'page_instagram'),

            'facebook' => SocialMedia::where('media_type', 'facebook')
                ->latest()
                ->paginate(6, ['*'], 'page_facebook'),

            'twitter' => SocialMedia::where('media_type', 'twitter')
                ->latest()
                ->paginate(6, ['*'], 'page_twitter'),

            'linkedin' => SocialMedia::where('media_type', 'linkedin')
                ->latest()
                ->paginate(6, ['*'], 'page_linkedin'),
        ];
        // $posts = SocialMedia::latest()->get()->groupBy('media_type');

        return view('media.socialmedia',compact('posts', 'tab'));
    }
    public function socialMediaAdd(){
        return view('media.socialmediaadd');
    }
    public function socialMediaStore(Request $request)
    {
        $request->validate([
            'platform'       => 'required|in:instagram,facebook,twitter,linkedin',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'image'          => 'required|image|max:10240',
            'school_name'    => 'required|string|max:255',
            'district_name'  => 'required|string|max:255',
            'training_date'  => 'required|date',
            'media_link'     => 'required|string',
        ]);

        $file = $request->file('image');
        $userId = Auth::id();
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder = "Okcl_Media/SocialMedia_{$userId}";

        try {
            // Upload to OneDrive
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            SocialMedia::create([
                'user_id'        => $userId,
                'media_type'       => $request->platform,
                'title'          => $request->title,
                'description'    => $request->description,
                'media_path'     => $result['path'] ?? null,
                'media_url'      => $result['url'] ?? null,
                'school_name'    => $request->school_name,
                'district_name'  => $request->district_name,
                'training_date'  => $request->training_date,
                'media_link'     => $request->media_link,
            ]);

            return redirect()->back()->with('success', 'Social media post added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }
    public function socialMediaUpdate(Request $request, $id)
    {
        $post = SocialMedia::findOrFail($id);

        $request->validate([
            'platform'       => 'required|in:instagram,facebook,twitter,linkedin',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'image'          => 'nullable|image|max:10240',
            'school_name'    => 'required|string|max:255',
            'district_name'  => 'required|string|max:255',
            'training_date'  => 'required|date',
            'media_link'     => 'required|string',
        ]);

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $userId = Auth::id();
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "Okcl_Media/SocialMedia_{$userId}";

            try {
                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

                $post->media_path = $result['path'] ?? $post->media_path;
                $post->media_url  = $result['url'] ?? $post->media_url;

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'File upload failed: ' . $e->getMessage());
            }
        }

        //  Update remaining fields
        $post->update([
            'media_type'     => $request->platform,
            'title'          => $request->title,
            'description'    => $request->description,
            'school_name'    => $request->school_name,
            'district_name'  => $request->district_name,
            'training_date'  => $request->training_date,
            'media_link'     => $request->media_link,
        ]);

        return redirect()->back()->with('success', 'Social media post updated successfully.');
    }
    public function printElectronic(Request $request){
        
        $tab = $request->get('tab', 'print');

        $posts = [
            'print' => SocialMedia::where('media_type', 'print')
                ->latest()
                ->paginate(8, ['*'], 'page_print'),

            'electronic' => SocialMedia::where('media_type', 'electronic')
                ->latest()
                ->paginate(6, ['*'], 'page_electronic'),

            'digital' => SocialMedia::where('media_type', 'digital')
                ->latest()
                ->paginate(6, ['*'], 'page_digital'),
        ];

        return view('media.print&electronic',compact('posts', 'tab'));
    }
    public function printElectronicAdd(){
        return view('media.print&electronicadd');
    }
    public function printElectronicStore(Request $request)
    {
        $request->validate([
            'platform'       => 'required|in:print,electronic,digital',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'image'          => 'required|image|max:10240',
            'publish_date'   => 'required|date',
            'media_link'     => 'nullable|string',
        ]);

        $file = $request->file('image');
        $userId = Auth::id();
        $filename = time() . '_' . $file->getClientOriginalName();
        $folder = "Okcl_Media/PrintElectronic_{$userId}";

        try {
            // Upload to OneDrive
            $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

            SocialMedia::create([
                'user_id'        => $userId,
                'media_type'     => $request->platform,
                'title'          => $request->title,
                'description'    => $request->description,
                'media_path'     => $result['path'] ?? null,
                'media_url'      => $result['url'] ?? null,
                'training_date'  => $request->publish_date,
                'media_link'     => $request->media_link,
            ]);

            return redirect()->back()->with('success', 'Media post added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }
    public function printElectronicUpdate(Request $request, $id)
    {
        $post = SocialMedia::findOrFail($id);

        $request->validate([
            'platform'       => 'required|in:print,electronic,digital',
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'image'          => 'nullable|image|max:10240',
            'publish_date'   => 'required|date',
            'media_link'     => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $userId = Auth::id();
            $filename = time() . '_' . $file->getClientOriginalName();
            $folder = "Okcl_Media/PrintElectronic_{$userId}";

            try {
                $result = $this->oneDrive->uploadDirect($file, $folder, $filename);

                $post->media_path = $result['path'] ?? $post->media_path;
                $post->media_url  = $result['url'] ?? $post->media_url;

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'File upload failed: ' . $e->getMessage());
            }
        }

        //  Update remaining fields
        $post->update([
            'media_type'     => $request->platform,
            'title'          => $request->title,
            'description'    => $request->description,
            'training_date'  => $request->publish_date,
            'media_link'     => $request->media_link,
        ]);

        return redirect()->back()->with('success', 'Media post updated successfully.');
    }
    public function destroy($id)
    {
        $post = SocialMedia::findOrFail($id);

        if (Auth::user()->role_id != 9) {
            abort(403);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }

}
