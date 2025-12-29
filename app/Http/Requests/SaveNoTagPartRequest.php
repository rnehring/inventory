<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveNoTagPartRequest extends FormRequest
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
            'part' => 'required|string|max:255',
            'bin' => 'required|string|max:50',
            'count' => 'required|numeric|min:0',
            'uom' => 'required|string|max:50',
            'by_weight' => 'nullable|boolean',
            'warehouse' => 'required|string|max:200',
            'lot_number' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'part.required' => 'Part number is required',
            'bin.required' => 'Bin is required',
            'count.required' => 'Count is required',
            'count.numeric' => 'Count must be a number',
            'count.min' => 'Count cannot be negative',
            'uom.required' => 'Unit of measure is required',
            'warehouse.required' => 'Warehouse is required',
        ];
    }
}
