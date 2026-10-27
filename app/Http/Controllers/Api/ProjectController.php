<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Models\Project;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService)
    {
    }

    public function index()
    {
        $projects = $this->projectService->getAllProjects();

        return response()->json([
            'message' => 'Projects fetched successfully.',
            'projects' => ProjectResource::collection($projects),
        ], 200);
    }
    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject(
            $request->validated()
        );

        return response()->json([
            'message' => 'Project created successfully.',
            'project' => new ProjectResource($project),
        ], 201);
    }
    // Get one project
    public function show(Project $project)
    {
        return response()->json([
            'message' => 'Project fetched successfully.',
            'project' => new ProjectResource($project),
        ], 200);
    }
    // Update project
    public function update( UpdateProjectRequest $request,Project $project) 
    {
        $project = $this->projectService->updateProject(
            $project,
            $request->validated()
        );
    
        return response()->json([
            'message' => 'Project updated successfully.',
            'project' => new ProjectResource($project),
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