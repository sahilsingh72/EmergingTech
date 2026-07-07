<?php

namespace Database\Seeders;

use App\Models\Batch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        Batch::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Batch 1',
                'description' => 'Original 100 schools',
                'is_active' => true,
            ]
        );

        Batch::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Batch 2',
                'description' => 'New 50 schools',
                'is_active' => true,
            ]
        );
    }
}
