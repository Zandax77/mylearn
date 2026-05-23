<?php

namespace App\Providers;

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
        if (!app()->runningInConsole()) {
            try {
                $school = \App\Models\SchoolSetting::first();
                if (!$school) {
                    $school = (object) ['name' => 'myLearn LMS', 'logo' => null];
                }
                view()->share('school', $school);
            } catch (\Exception $e) {
                view()->share('school', (object) ['name' => 'myLearn LMS', 'logo' => null]);
            }
        }
    }
}
