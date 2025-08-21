<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers', 'email')->ignore($this->route('customer')->id),
            ],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:255',
            'payment_terms' => 'required|in:cash,net_15,net_30,net_45,net_60',
            'credit_limit' => 'required|numeric|min:0|max:999999999.99',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Customer name is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered to another customer.',
            'payment_terms.required' => 'Payment terms are required.',
            'payment_terms.in' => 'Invalid payment terms selected.',
            'credit_limit.required' => 'Credit limit is required.',
            'credit_limit.numeric' => 'Credit limit must be a valid number.',
            'status.required' => 'Customer status is required.',
        ];
    }
}