<?php

namespace App\Http\Middleware;

use App\Models\Batch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveBatch
{
    public function handle(Request $request, Closure $next): Response
    {
        $batchId = session('active_batch_id');

        $validBatch = Batch::whereKey($batchId)
            ->where('is_active', true)
            ->exists();

        if (!$validBatch) {
            $defaultBatch = Batch::where('is_active', true)
                ->orderByDesc('id')
                ->first();

            if ($defaultBatch) {
                session(['active_batch_id' => $defaultBatch->id]);
            }
        }

        return $next($request);
    }
}
