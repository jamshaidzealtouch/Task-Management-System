<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => [
                'required',
                'exists:projects,id',
            ],

            'created_by' => [
                'required',
                'exists:users,id',
            ],

            'assignee_id' => [
                'nullable',
                'exists:users,id',
            ],

            'assignment_status' => [
                'nullable',
                'string',
                'max:50',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:2048',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'priority' => [
                'required',
                new Enum(TaskPriority::class),
            ],

            'status' => [
                'required',
                new Enum(TaskStatus::class),
            ],
        ];
    }
}