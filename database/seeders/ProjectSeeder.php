<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::factory()
            ->count(3)
            ->hasTasks(5)
            ->create();
    }
}