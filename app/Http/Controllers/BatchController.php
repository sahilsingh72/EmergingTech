<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => [
                'required',
                'integer',
                'exists:batches,id',
            ],
        ]);

        $batch = Batch::whereKey($validated['batch_id'])
            ->where('is_active', true)
            ->firstOrFail();

        session([
            'active_batch_id' => $batch->id,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', "Switched to {$batch->name}");
    }
}
