<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user') ?? $this->input('id');
        
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'initials' => 'required|string|max:10',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'plant' => 'required|string|max:255',
            'user_type' => 'required|integer|in:1,2',
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'initials.required' => 'Initials are required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered',
            'plant.required' => 'Plant is required',
            'user_type.required' => 'User type is required',
            'user_type.in' => 'Invalid user type',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }
}
