<?php

namespace App\Http\Requests\PowerGenerator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

class UpdateInfoRequest extends FormRequest
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
                 'name'         => 'required|string',
                'location'     => 'required|string',
                'phones'       => 'nullable|array',
                'kiloPrice'=>'required|numeric',
                'afterPaymentFrequency'=>'nullable|int',
                'day'=>['required', Rule::in(array_values((new ReflectionClass(\App\Types\DaysOfWeek::class))->getConstants()))],
                'spendingType'=>['required', Rule::in(array_values((new ReflectionClass(\App\Types\SpendingTypes::class))->getConstants()))],
          ];
    }
}
