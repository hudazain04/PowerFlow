<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCustomerRequest extends FormRequest
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
        $generatorId = auth()->user()->powerGenerator->id;

        return [
            'action' => 'required|in:approve,reject',
            'counter_number' => 'required_if:action,approve|string|unique:counters,number',
            'admin_notes' => 'nullable|string|max:500'
        ];
    }
}
