<?php

namespace App\Http\Requests\SubscriptionRequest;

use App\Types\SubscriptionTypes;
use App\Types\UserTypes;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
//            'period'       => 'required|integer',
            'planPrice_id' => 'required|exists:plan_prices,id',
        ];
        if ( $this->user()->hasRole('admin')) {
            $rules['type'] = [
                'required',
                'string',
                Rule::in((new \ReflectionClass(SubscriptionTypes::class))->getConstants()),
            ];
        } else {
            $rules['type'] = [
                'nullable',
                'string',
                Rule::in((new \ReflectionClass(SubscriptionTypes::class))->getConstants()),
            ];
        }

        return $rules;
    }
}
