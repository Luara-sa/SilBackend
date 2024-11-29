<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'phone_number' => 'required|string',
            'gender' => 'required|string|in:M,F',
            'password' => 'required|string|min:8|confirmed', // Add 'confirmed' rule

        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.name_required'),
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
            'email.unique' => __('validation.email_unique'),
            'phone_number.required' => __('validation.phone_number_required'),
            'gender.required' => __('validation.gender_required'),
            'gender.in' => __('validation.gender_invalid'),
            'password.required' => __('validation.password_required'),
            'password.min' => __('validation.password_min'),
            'password.confirmed' => __('validation.password_confirmed'), // Add confirmation message
        ];
    }
}
