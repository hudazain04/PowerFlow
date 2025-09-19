<?php

namespace App\Http\Requests\Payment;

use App\Models\Counter;
use App\Types\SpendingTypes;
use Illuminate\Foundation\Http\FormRequest;

class SpendingPayRequest extends FormRequest
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
        $counter_id=$this->route('counter_id');
        $counter=Counter::find($counter_id);

        if ($counter->spendingType === SpendingTypes::Before) {
            return [
                'kilos' => 'required|numeric|min:1',
            ];
        }
        return
        [
            'date'=>'nullable|dateTime',
        ];
    }
}
