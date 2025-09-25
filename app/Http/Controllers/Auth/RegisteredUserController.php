<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Coordinator;
use App\Models\District;
use App\Models\DLC;
use App\Models\Role;
use App\Models\School;
use App\Models\Trainer;
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
    public function create(): View
    {
        $roles = Role::all();   // fetch all roles
        $trainers = Trainer:: select('trainer_name', 'trainer_id')->get();
        $coordinators = Coordinator:: select('coordinator_name', 'coordinator_id')->get();
        $zones = District::select('DSM_ZONEID')->distinct()->orderBy('DSM_ZONEID', 'asc')->get();
        $dstmst = District::select('DSM_DSCD', 'DSM_STCD', 'DSM_DSNM', 'DSM_ZONEID')->get();

        return view('auth.register', compact('roles', 'coordinators', 'dstmst', 'zones', 'trainers'));
    }

    public function getDistricts($zone_id)
    {
        $districts = District::where('DSM_ZONEID', $zone_id)->orderBy('DSM_DSNM', 'asc')->get(['DSM_DSCD', 'DSM_DSNM']);
        return response()->json($districts);
    }

    public function getSchools($district_id)
    {
        $schools = School::where('scm_dist_id', $district_id)->orderBy('scm_name', 'asc')->get(['scm_id', 'scm_name', 'scm_udise_code']);
        return response()->json($schools);
    }

   public function store(Request $request)
{
    $request->validate([
        'email' => ['required','email','unique:users,email'],
        'password' => ['required','confirmed', Rules\Password::defaults()],
        'role_id' => ['required','exists:roles,id'],
    ]);

    $role = Role::find($request->role_id);

    // Validate role-specific fields
    if($role->name === 'Coordinator') {
        $request->validate([
            'coordinator_id'=>'required',
            'zone_id'=>'required',
            'district_id'=>'required',
            'institute_id'=>'required',
        ]);
    }
    elseif($role->name === 'Trainer') {
        $request->validate([
            'trainer_id'=>'required',
            'zone_id'=>'required',
            'district_id'=>'required',
            'institute_id'=>'required',
            'assignUnder_id'=>'required',
        ]);
    }
    elseif($role->name === 'Institute') {
        $request->validate([
            'name'=>'required',
            'zone_id'=>'required',
            'district_id'=>'required',
            'institute_id'=>'required',
            'assignUnder_id'=>'required',
        ]);
    }
    else { // OCAC/OKCL
        $request->validate([
            'name'=>'required'
        ]);
    }

    $user = new User();
    $user->role_id = $request->role_id;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);

    if($role->name === 'Trainer') {
        $trainer = Trainer::find($request->trainer_id);
        if(!$trainer){
            return back()->withErrors(['trainer_id'=>'Selected trainer not found']);
        }
        $user->name = $trainer->trainer_name; // fill name from selected trainer
        $user->trainer_id = $request->trainer_id;
        $user->zone_id = $request->zone_id;
        $user->district_id = $request->district_id;
        $user->institute_id = $request->institute_id;
        $user->assignUnder_id = $request->assignUnder_id;
    } 
    elseif($role->name === 'Coordinator') {
        $coordinator = Coordinator::find($request->coordinator_id);
        $user->name = $coordinator->coordinator_name;   // ✅ auto fill coordinator name
        $user->coordinator_id = $request->coordinator_id;
        $user->zone_id = $request->zone_id;
        $user->district_id = $request->district_id;
        $user->institute_id = $request->institute_id;
    }
    elseif($role->name === 'Institute') {
        $user->name = $request->name;   // HM Name
        $user->zone_id = $request->zone_id;
        $user->district_id = $request->district_id;
        $user->institute_id = $request->institute_id;
        $user->assignUnder_id = $request->assignUnder_id;
    }
    else { // OCAC/OKCL
        $user->name = $request->name;
    }
        $user->save();

        return redirect()->back()->with('success',"New user ({$role->name}) created successfully!");
    }

    // public function store(Request $request): RedirectResponse
    // {
    //     // dd($request->all());

    //     $roles = Role::all();
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //         'role_id' => ['required', 'exists:roles,id'],  // validate role
    //     ]);
    //     $role = Role::find($request->role_id);

    //     $user = new User();
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->password = Hash::make($request->password);
    //     $user->role_id = $request->role_id;
    //     $user->zone_id = $request->zone_id;

    //     if ($role->name === "DLC" || $role->name === "Coordinator") {
    //         $user->district_id = $request->district_id;
    //     }

    //     if ($role->name === "Institute" || $role->name === "Trainer") {
    //         $user->district_id = $request->district_id2;  // optional if needed
    //         $user->dlc_id = $request->dlc_id; // copied from dlc_id2 via JS
    //         $user->block_id = $request->block_id;
    //         $user->institute_id = $request->institute_id;
    //     }

    //     $user->save();

    //     // event(new Registered($user));

    //     // Auth::login($user);

    //     // return redirect(route('dashboard', absolute: false));
    //     return redirect()->back()->with('success', "New user with role {$role->name} created successfully!");

    // }

    
}
