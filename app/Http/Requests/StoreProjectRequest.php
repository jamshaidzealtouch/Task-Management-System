<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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

            'status' => 'required|in:Pending,In Progress,Completed,Cancelled',

        ];
    }
}