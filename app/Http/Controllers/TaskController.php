<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

use App\Models\Project;

use App\Models\Task;

class TaskController extends Controller
{
   //show all data
    public function index(Project $project)
    {
        $tasks = $project->tasks;

        return view('tasks.index', compact('project', 'tasks'));
    }

    
    //create new user data
    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

   // add new user data
    public function store(StoreTaskRequest $request, Project $project)
    {
        $data = $request->validated();
    
        $data['project_id'] = $project->id;
    
        Task::create($data);
    
        return redirect()
            ->route('projects.tasks.index', $project->id)
            ->with('success', 'Task added successfully.');
    }

    

    //show data 
    public function show(Project $project, Task $task)
    {
        return view('tasks.show', compact('project', 'task'));
    }
    

   public function edit(Project $project, Task $task)
{
    return view('tasks.edit', compact('project', 'task'));
}

  
//update data
public function update(UpdateTaskRequest $request, Project $project, Task $task)
{
    $task->update($request->validated());

    return redirect()
        ->route('projects.tasks.index', $project->id)
        ->with('success', 'Task updated successfully.');
}



    
    public function destroy(Project $project, Task $task)
{
    $task->delete();

    return redirect()
        ->route('projects.tasks.index', $project->id)
        ->with('success', 'Task deleted successfully.');
}


public function trash(Project $project)
{
    $tasks = Task::onlyTrashed()
                ->where('project_id', $project->id)
                ->get();

    return view('tasks.trash', compact('project', 'tasks'));
}

public function restore(Project $project, $task)
{
    $task = Task::onlyTrashed()->findOrFail($task);

    $task->restore();

    return redirect()
        ->route('projects.tasks.trash', $project->id)
        ->with('success', 'Task restored successfully.');
}

}