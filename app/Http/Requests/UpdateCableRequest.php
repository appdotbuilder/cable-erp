<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCableRequest extends FormRequest
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
            'barcode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cables', 'barcode')->ignore($this->route('cable')->id),
            ],
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0|max:999999.99',
            'stock_quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit_of_measure' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,discontinued',
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
            'barcode.required' => 'Barcode is required.',
            'barcode.unique' => 'This barcode is already used by another cable.',
            'name.required' => 'Cable name is required.',
            'size.required' => 'Cable size is required.',
            'type.required' => 'Cable type is required.',
            'unit_price.required' => 'Unit price is required.',
            'unit_price.numeric' => 'Unit price must be a valid number.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'minimum_stock.required' => 'Minimum stock level is required.',
            'unit_of_measure.required' => 'Unit of measure is required.',
        ];
    }
}