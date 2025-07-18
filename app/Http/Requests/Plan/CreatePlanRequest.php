<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanRequest extends FormRequest
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
            'name'=>'string|required',
            'description'=>'string|required',
            'target'=>'string|required',
            'monthlyPrice'=>'int|required',
            'image'=>'image|sometimes',
            'features'=>'array|required',
            'features.*.value'=>'required|int',
            'features.*.id'=>'required|exists:features',
            'planPrices'=>'array|required',
            'planPrices.*.period'=>'int|required',
            'planPrices.*.discount'=>'int|required',
        ];
    }
}
