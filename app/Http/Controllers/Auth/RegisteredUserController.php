<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\DLC;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    //FOR INSTITUTE AND TRAINER
    public function index()
    {
        $dlcs = DB::table('dlc_mst')->get();
        return view('register', compact('dlcs'));
    }

                // Get blocks under selected DLC
    public function getBlocks($dlc_id)
    {
        $dlc = DB::table('dlc_mst')->where('dlc_id', $dlc_id)->first();

        if (!$dlc) {
            return response()->json([]);
        }

        $blocks = DB::table('school_mst')
            ->where('scm_dist', $dlc->dlc_dst)
            ->select('scm_blockid', 'scm_block')
            ->distinct()
            ->get();

        return response()->json($blocks);
    }

            // Get schools under selected block
    public function getSchools($block_id)
    {
        $schools = DB::table('school_mst')
            ->where('scm_blockid', $block_id)
            ->select('scm_id', 'scm_name')
            ->get();

        return response()->json($schools);
    }


    public function create(): View
    {
        $roles = Role::all();   // fetch all roles
        $dlcdsts = DLC::select('dlc_id', 'dlc_cnm', 'dlc_dst', 'dlc_dstID')->get();

        return view('auth.register', compact('roles', 'dlcdsts'));
    }

    //FOR DLC
    public function getDlcs($district_id)
    {
        return DLC::where('dlc_dstID', $district_id)
                    ->get(['dlc_id', 'dlc_cnm']);
    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());

        $roles = Role::all();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],  // validate role
        ]);
        $role = Role::find($request->role_id);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;

        if ($role->name === "DLC") {
            $user->district_id = $request->district_id;
            $user->dlc_id = $request->dlc_id;
        }

        if ($role->name === "Institute" || $role->name === "Trainer") {
            $user->district_id = $request->district_id2;  // optional if needed
            $user->dlc_id = $request->dlc_id; // copied from dlc_id2 via JS
            $user->block_id = $request->block_id;
            $user->institute_id = $request->institute_id;
        }

        $user->save();

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));
        return redirect()->back()->with('success', "New user with role {$role->name} created successfully!");

    }

    
}
