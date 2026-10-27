<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'project_id' => [
                'sometimes',
                'required',
                'exists:projects,id',
            ],

            'created_by' => [
                'sometimes',
                'required',
                'exists:users,id',
            ],

            'assignee_id' => [
                'sometimes',
                'nullable',
                'exists:users,id',
            ],

            'assignment_status' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
            ],

            'title' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'required',
                'string',
            ],

            'due_date' => [
                'sometimes',
                'required',
                'date',
            ],

            'priority' => [
                'sometimes',
                'required',
                new Enum(TaskPriority::class),
            ],

            'status' => [
                'sometimes',
                'required',
                new Enum(TaskStatus::class),
            ],
        ];
    }
}