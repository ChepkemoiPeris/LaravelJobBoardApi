<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'company_user';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [ 
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'min_salary' => ['nullable', 'integer'],
            'max_salary' => ['nullable', 'integer', 'gte:min_salary'],
            'job_type_id' => ['required', 'exists:job_types,id'],
            'status' => ['sometimes', 'in:active,inactive'],
            'deadline' => ['required', 'date', 'after:today']
        ];
    }
}
