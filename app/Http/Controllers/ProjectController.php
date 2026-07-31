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
        Project::create([

            'user_id' => session('user_id'),

            'title' => $request->title,

            'description' => $request->description,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project added successfully.');
    }

   
    public function show($id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();

        return view('projects.edit', compact('project'));
    }

    
    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();

        $project->update([

            'title' => $request->title,

            'description' => $request->description,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

   
    public function destroy($id)
    {
        $project = Project::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}