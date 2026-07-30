<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function schoolOneDriveRoot(object $school, string $districtName, string $schoolName): string
    {
        $batchId = session('active_batch_id') ?: ($school->batch_id ?? null);

        return "EmergingTech/batch{$batchId}/{$districtName}/{$schoolName}";
    }
}
