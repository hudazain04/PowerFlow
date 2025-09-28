<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class counterUpdateRequest extends FormRequest
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
//            'number' => 'sometimes|required',
            'user_id' => 'sometimes|required',
            'box_id' => 'nullable|exists:electrical_boxes,id',
            'physical_device_id'=>'nullable|string',
        ];
    }
}
