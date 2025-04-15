<?php

namespace App\Http\Requests;

use App\Types\UserTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'first_name' => 'required_if:role,employee|string',
            'last_name' => 'required_if:role,employee|string',
            'name' => 'required_unless:role,employee|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|numeric',
            'generator_id' => 'required_if:role,employee|exists:power_generators,id',
            'role' => ['required', 'string', Rule::in(UserTypes::$statuses)],

        ];
    }
}
