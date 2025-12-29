<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryCountRequest extends FormRequest
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
            'part' => 'required|exists:inventory,id',
            'count' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'part.required' => 'Part ID is required',
            'part.exists' => 'The selected part does not exist',
            'count.required' => 'Count is required',
            'count.numeric' => 'Count must be a number',
            'count.min' => 'Count cannot be negative',
        ];
    }
}
