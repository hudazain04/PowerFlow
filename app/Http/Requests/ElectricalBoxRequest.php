<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ElectricalBoxRequest extends FormRequest
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
        $boxId = $this->route('id');
        return [
//            'number' => [
//                'required',
//                'string',
//                Rule::unique('electrical_boxes')->ignore($boxId)
//            ],
            'capacity' => 'required|integer|min:1',
            'location'=>'required|string|max:500',
            'maps'=>'array',
            'maps.x'=>'numeric|required',
            'maps.y'=>'numeric|required',
            'area_id'=>'nullable|integer|exists:areas,id'
        ];
    }
}
