<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ProjectStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
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

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',
            'organization_id' => 'required|exists:organizations,id',

            'status' => [
    'required',
    new Enum(ProjectStatus::class),
],

        ];
    }
}