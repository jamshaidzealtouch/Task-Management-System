<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'title' => 'required|max:255',

            'description' => 'required',

            'due_date' => 'required|date',

            'priority' => [new Enum(TaskPriority::class)],

            'status' => [new Enum(TaskStatus::class)],

        ];
    }
}