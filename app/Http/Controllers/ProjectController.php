<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
   
    public function index()
    {
        $projects = Project::where('user_id', session('user_id'))->get();

        return view('projects.index', compact('projects'));
    }

   
    public function create()
    {
        return view('projects.create');
    }



    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
    
        $data['user_id'] = session('user_id');
    
        Project::create($data);
    
        return redirect()
            ->route('projects.index')
            ->with('success', 'Project added successfully.');
    }

   


public function show(Project $project)
{
    if ($project->user_id != session('user_id')) {
        abort(404);
    }

    return view('projects.show', compact('project'));
}


public function edit(Project $project)
{
    if ($project->user_id != session('user_id')) {
        abort(404);
    }

    return view('projects.edit', compact('project'));
}



    
public function update(UpdateProjectRequest $request, Project $project)
{
    if ($project->user_id != session('user_id')) {
        abort(404);
    }

    $project->update($request->validated());

    return redirect()
        ->route('projects.index')
        ->with('success', 'Project updated successfully.');
}
   
public function destroy(Project $project)
{
    if ($project->user_id != session('user_id')) {
        abort(404);
    }

    $project->delete();

    return redirect()
        ->route('projects.index')
        ->with('success', 'Project deleted successfully.');
}


}