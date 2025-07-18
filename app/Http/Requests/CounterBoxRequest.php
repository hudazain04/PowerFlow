<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterBoxRequest extends FormRequest
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
            'counterId' => 'required|exists:counters,id',
            'boxId' => 'required|exists:electrical_boxes,id'
        ];
    }
}
