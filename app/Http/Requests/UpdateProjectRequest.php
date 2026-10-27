<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Project update validation
    public function rules(): array
    {
        return [
            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                new Enum(ProjectStatus::class),
            ],
        ];
    }
}