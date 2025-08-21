<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'items' => 'sometimes|array|min:1',
            'items.*.cable_id' => 'required_with:items|exists:cables,id',
            'items.*.description' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01|max:999999.99',
            'items.*.unit_price' => 'required_with:items|numeric|min:0|max:999999.99',
            'items.*.line_total' => 'required_with:items|numeric|min:0|max:999999999.99',
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
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'due_date.required' => 'Due date is required.',
            'due_date.after_or_equal' => 'Due date must be on or after the invoice date.',
            'status.required' => 'Invoice status is required.',
            'items.min' => 'At least one invoice item is required.',
            'items.*.cable_id.exists' => 'Selected cable does not exist.',
            'items.*.description.required_with' => 'Item description is required.',
            'items.*.quantity.required_with' => 'Item quantity is required.',
            'items.*.quantity.min' => 'Item quantity must be greater than 0.',
            'items.*.unit_price.required_with' => 'Item unit price is required.',
            'items.*.line_total.required_with' => 'Item line total is required.',
        ];
    }
}