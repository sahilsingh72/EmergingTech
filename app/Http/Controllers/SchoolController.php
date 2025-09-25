<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    // Show page with dropdown
    public function index()
    {
        $dlcs = DB::table('dlc_mst')->get();   // fetch all DLCs
        return view('index', compact('dlcs'));
    }

    // Fetch schools by DLC
    public function getByDlc($dlc_id)
    {
        // Get the selected DLC district
        $dlc = DB::table('dlc_mst')->where('dlc_id', $dlc_id)->first();

        if (!$dlc) {
            return response()->json([]);
        }

        // Match with schools
        $schools = DB::table('school_mst')
            ->where('scm_dist', $dlc->dlc_dst)
            ->select('scm_blockid', 'scm_block', 'scm_distid', 'scm_name','scm_dist')
            ->get();

        return response()->json($schools);
    }
}
