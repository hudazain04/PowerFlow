<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class DeleteERequest extends FormRequest
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
        $generatorId = auth()->user()->powerGenerator->id;

        return [
//            'ids' => [
//                'required',
//                'array',
//                function ($attribute, $value, $fail) use ($generatorId) {
//                    $invalidIds = DB::table('electrical_boxes')
//                        ->whereIn('id', $value)
//                        ->where('generator_id', '!=', $generatorId)
//                        ->pluck('id');
//
//                    if ($invalidIds->isNotEmpty()) {
//                        $fail('Some boxes do not belong to your generator: ' . $invalidIds->implode(', '));
//                    }
//                }
//            ],
//            'ids.*' => 'required|integer|exists:electrical_boxes,id'
//        ];
            'id' => [
                'sometimes',
                'required_without:ids',
                'integer',
                'exists:electrical_boxes,id',
                function ($attribute, $value, $fail) use ($generatorId) {
                    $belongsToGenerator = DB::table('electrical_boxes')
                        ->where('id', $value)
                        ->where('generator_id', $generatorId)
                        ->exists();

                    if (!$belongsToGenerator) {
                        $fail('The selected box does not belong to your generator.');
                    }
                }
            ],
            'ids' => [
                'sometimes',
                'required_without:id',
                'array',
                function ($attribute, $value, $fail) use ($generatorId) {
                    $invalidIds = DB::table('electrical_boxes')
                        ->whereIn('id', $value)
                        ->where('generator_id', '!=', $generatorId)
                        ->pluck('id');

                    if ($invalidIds->isNotEmpty()) {
                        $fail('Some boxes do not belong to your generator: ' . $invalidIds->implode(', '));
                    }
                }
            ],
            'ids.*' => 'sometimes|integer|exists:electrical_boxes,id'
        ];
    }
}
