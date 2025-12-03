<?php

namespace App\Providers;

use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Observers\AssignmentSubmissionObserver;
use App\Observers\UserObserver;
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
        User::observe(UserObserver::class);
        AssignmentSubmission::observe(AssignmentSubmissionObserver::class);
    }
}
