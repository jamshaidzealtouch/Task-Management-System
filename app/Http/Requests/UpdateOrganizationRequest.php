<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            
            'name' => [

                'required',

                'string',

                'max:255',

                Rule::unique('organizations', 'name')->ignore($this->organization),

            ],

            'description' => 'nullable|string|max:1000',
        ];
    }
}
