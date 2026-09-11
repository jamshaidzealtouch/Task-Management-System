<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    // Get all projects
    public function index()
    {
        $projects = Project::all();

        return response()->json([
            'message' => 'Projects fetched successfully.',
            'projects' => $projects,
        ], 200);
    }
    // Create project
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'organization_id' => $request->organization_id,
        ]);

        return response()->json([
            'message' => 'Project created successfully.',
            'project' => $project,
        ], 201);
    }
    // Get one project
    public function show(Project $project)
    {
        return response()->json([
            'message' => 'Project fetched successfully.',
            'project' => $project,
        ], 200);
    }
    // Update project
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'organization_id' => $request->organization_id,
        ]);

        return response()->json([
            'message' => 'Project updated successfully.',
            'project' => $project,
        ], 200);
    }
    // Delete project
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully.'
        ], 200);
    }
}