<?php

namespace App\Http\Requests\Complaint;

use App\Types\ComplaintStatusTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCutComplaintRequest extends FormRequest
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
            'description'=>'required|string',
            'status'=>['required',Rule::in(array_values((new \ReflectionClass(ComplaintStatusTypes::class))->getConstants()))]
        ];
    }
}
