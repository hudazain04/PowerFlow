<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'generator_id' => 'required|exists:power_generators,id',
//            'user_notes' => 'nullable|string|max:500',
//            'address' => 'required|string|max:255',
//            'estimated_usage' => 'nullable|numeric|min:0'
        ];
    }
}
