<?php

namespace App\Http\Requests\SubscriptionRequest;

use App\Types\SubscriptionTypes;
use App\Types\UserTypes;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;

class CreateSubscriptionRequestRequest extends FormRequest
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
        $rules = [
            'name'         => 'required|string',
            'location'     => 'required|string',
            'phones'       => 'nullable|array',
            'planPrice_id' => 'required|exists:plan_prices,id',
            'kiloPrice'=>'required|int',
            'afterPaymentFrequency'=>'nullable|int',
            'day'=>['required', Rule::in(array_values((new ReflectionClass(\App\Types\DaysOfWeek::class))->getConstants()))],
            'spendingType'=>['required', Rule::in(array_values((new ReflectionClass(\App\Types\SpendingTypes::class))->getConstants()))],
        ];
        return $rules;
    }
}
