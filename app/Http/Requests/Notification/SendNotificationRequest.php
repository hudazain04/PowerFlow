<?php

namespace App\Http\Requests\Notification;

use App\Types\NotificationTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

class SendNotificationRequest extends FormRequest
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
            'title' => 'required|string',
            'body' => 'required|string',
            'ids' => 'nullable|array',
            'type' => ['required', Rule::in(NotificationTypes::toArray())],
        ];
    }
}
