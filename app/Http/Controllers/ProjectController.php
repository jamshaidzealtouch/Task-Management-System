<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    
    public function index()
    {
        $projects = Project::all();

    return view('projects.index', compact('projects'));
    }

   
    public function create()
    {
        return view('projects.create');
    }

    
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required',
        ]);

        Project::create([

            'title' => $request->title,
    
            'description' => $request->description,
    
            'start_date' => $request->start_date,
    
            'end_date' => $request->end_date,
    
            'status' => $request->status,
    
        ]);

        return redirect()
        ->route('projects.index')
        ->with('success', 'Project added successfully.');
    
        return redirect()->route('projects.index');
    }

   
    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        return view('projects.show', compact('project'));
    
    }

    
    public function edit(string $id)
    {
        
    $project = Project::findOrFail($id);

    return view('projects.edit', compact('project'));
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
    
            'title' => 'required|max:255',
    
            'description' => 'required',
    
            'start_date' => 'required',
    
            'end_date' => 'required',
    
            'status' => 'required',
    
        ]);
    
        $project = Project::findOrFail($id);
    
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

   
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);

        $project->delete();
    
        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
