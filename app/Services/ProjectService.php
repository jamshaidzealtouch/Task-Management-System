<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function getAllProjects()
    {
        return Project::all();
    }

    public function createProject(array $data): Project
    {
        return Project::create($data);
    }
    public function updateProject(Project $project, array $data): Project
{
    $project->update($data);

    return $project;
}
}