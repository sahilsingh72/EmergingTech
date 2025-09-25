<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    // Show initial page
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
}
