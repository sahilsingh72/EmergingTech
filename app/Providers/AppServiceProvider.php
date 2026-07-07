<?php

namespace App\Providers;

use App\Models\Batch;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.navbar', function ($view) {
            $batches = Batch::where('is_active', true)
                ->orderBy('id')
                ->get();

            $activeBatch = $batches->firstWhere(
                'id',
                (int) session('active_batch_id')
            );

            $view->with([
                'availableBatches' => $batches,
                'activeBatch' => $activeBatch,
            ]);
        });
    }
}
