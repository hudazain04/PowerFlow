<?php

namespace App\Http\Requests\Action;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

class CreateActionRequest extends FormRequest
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
            'counter_id'=>'required|int|exists:counters,id',
            'generator_id'=>'required|int|exists:generators,id',
            'type'=>['required', Rule::in(array_values((new ReflectionClass(\App\Types\ActionTypes::class))->getConstants()))],
            'priority'=>'required|int',
            'relatedData'=>'nullable',
        ];
    }
}
