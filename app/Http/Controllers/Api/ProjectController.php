<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return response()->json([
            'message' => 'Projects fetched successfully.',
            'projects' => $projects,
        ], 200);
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'nullable|in:pending,in_progress,completed,cancelled',
        'organization_id' => 'nullable|exists:organizations,id',
    ]);

    $project = Project::create([
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => $request->status ?? 'pending',
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
 //update project
 public function update(Request $request, Project $project)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'organization_id' => 'nullable|exists:organizations,id',
    ]);

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
// delete project
public function destroy(Project $project)
{
    $project->delete();

    return response()->json([
        'message' => 'Project deleted successfully.'
    ], 200);
}
}