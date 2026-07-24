<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'title' => 'CRM System',
            'description' => 'Customer Relationship Management System',
            'start_date' => '2026-07-23',
            'end_date' => '2026-08-15',
            'status' => 'Pending',
        ]);

        Project::create([
            'title' => 'E-Commerce Website',
            'description' => 'Online Shopping Website',
            'start_date' => '2026-08-01',
            'end_date' => '2026-09-10',
            'status' => 'In Progress',
        ]);

        Project::create([
            'title' => 'School Management',
            'description' => 'School Management System',
            'start_date' => '2026-09-05',
            'end_date' => '2026-10-20',
            'status' => 'Completed',
        ]);
    }
}