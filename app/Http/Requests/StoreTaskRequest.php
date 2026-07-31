<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|max:255',
            'description' => 'required',
            'due_date'    => 'required|date',
            'priority'    => 'required|in:Low,Medium,High',
            'status'      => 'required|in:To_Do,In_Progress,Completed',
        ];
    }
}