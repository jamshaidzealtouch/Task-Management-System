<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    //validation rules
    public function rules(): array
    {
        return [

            'title' => 'required|max:255',

            'description' => 'required',

            'due_date' => 'required|date',

            'priority' => 'required|in:Low,Medium,High',

            'status' => 'required|in:To_Do,In_Progress,Completed',

        ];
    }
}