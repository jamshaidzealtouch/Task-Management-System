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
            'title' => 'School Management System',
            'description' => 'Manage students, teachers and classes.',
            'start_date' => '2026-09-05',
            'end_date' => '2026-10-20',
            'status' => 'Completed',
        ]);

        Project::create([
            'title' => 'Hospital Management System',
            'description' => 'Manage patients, doctors and appointments.',
            'start_date' => '2026-08-10',
            'end_date' => '2026-10-05',
            'status' => 'In Progress',
        ]);

        Project::create([
            'title' => 'Inventory Management System',
            'description' => 'Track products, stock and suppliers.',
            'start_date' => '2026-07-15',
            'end_date' => '2026-08-30',
            'status' => 'Pending',
        ]);

        Project::create([
            'title' => 'Library Management System',
            'description' => 'Manage books, members and issue records.',
            'start_date' => '2026-09-01',
            'end_date' => '2026-11-01',
            'status' => 'Cancelled',
        ]);

        Project::create([
            'title' => 'Task Management System',
            'description' => 'Manage projects, tasks and team workflow.',
            'start_date' => '2026-07-28',
            'end_date' => '2026-09-15',
            'status' => 'Completed',
        ]);
    }
}