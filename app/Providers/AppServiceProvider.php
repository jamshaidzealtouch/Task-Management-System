<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\Invitation;

use App\Policies\OrganizationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\InvitationPolicy;

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
        // Organization policy
        Gate::policy(
            Organization::class,
            OrganizationPolicy::class
        );

        // Project policy
        Gate::policy(
            Project::class,
            ProjectPolicy::class
        );

        // Task policy
        Gate::policy(
            Task::class,
            TaskPolicy::class
        );

        // Invitation policy
        Gate::policy(
            Invitation::class,
            InvitationPolicy::class
        );
    }
}