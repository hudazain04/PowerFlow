<?php

namespace App\Http\Requests;

use App\Types\SpendingTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

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
            'user_notes' => 'required|string|max:500',
            'box_id'=>'required|int|exists:electrical_boxes,id',
            'spendingType'=>['required', Rule::in(array_values((new ReflectionClass(\App\Types\SpendingTypes::class))->getConstants()))],
//            'address' => 'required|string|max:255',
//            'estimated_usage' => 'nullable|numeric|min:0'
        ];
    }


}
