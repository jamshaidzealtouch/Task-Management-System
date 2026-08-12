@extends('layouts.app')


@section('content')


<div class="container">


    <h1 class="mb-4">My Tasks</h1>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    @if($tasks->count() > 0)


        <div class="table-responsive">


            <table class="table table-bordered">


                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Project</th>

                        <th>Task Title</th>

                        <th>Description</th>

                        <th>Priority</th>

                        <th>Due Date</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                    @foreach($tasks as $task)


                        <tr>


                            <td>
                                {{ $task->id }}
                            </td>


                            <td>
                                {{ $task->project->title }}
                            </td>


                            <td>
                                {{ $task->title }}
                            </td>


                            <td>
                                {{ $task->description }}
                            </td>


                            <td>
                                {{ ucfirst($task->priority->value) }}
                            </td>


                            <td>
                                {{ $task->due_date }}
                            </td>


                            <td>
                                {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                            </td>


                            <td>

                                <form
                                    action="{{ route('my.tasks.status', $task->id) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('PATCH')


                                    <select
                                        name="status"
                                        class="form-select mb-2"
                                    >


                                        <option
                                            value="to_do"
                                            {{ $task->status->value === 'to_do' ? 'selected' : '' }}
                                        >

                                            To Do

                                        </option>


                                        <option
                                            value="in_progress"
                                            {{ $task->status->value === 'in_progress' ? 'selected' : '' }}
                                        >

                                            In Progress

                                        </option>


                                        <option
                                            value="completed"
                                            {{ $task->status->value === 'completed' ? 'selected' : '' }}
                                        >

                                            Completed

                                        </option>


                                    </select>


                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-sm"
                                    >

                                        Update Status

                                    </button>


                                </form>

                            </td>


                        </tr>


                    @endforeach


                </tbody>


            </table>


        </div>


    @else


        <div class="alert alert-info">

            No tasks have been assigned to you.

        </div>


    @endif


</div>


@endsection