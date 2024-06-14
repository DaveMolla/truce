<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameRequest extends FormRequest
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
    public function rules()
    {
        return [
            'bet_amount' => 'required|numeric|min:5',
            'selected_numbers' => 'required|min:1',
            // 'selected_numbers' => ['required', 'string', function($attribute, $value, $fail) {
            //     $numbers = explode(',', $value);
            //     if (count($numbers) < 5) {
            //         $fail('At least 5 numbers must be selected.');
            //     }
            // }],
            'call_speed' => 'required|string|in:very_fast,fast',
            'caller_language' => 'required|string',
            'winning_pattern' => 'required|exists:winning_patterns,id',
        ];
    }

    public function messages()
    {
        return [
            'bet_amount.required' => 'The bet amount is required.',
            'bet_amount.numeric' => 'The bet amount must be a number.',
            'bet_amount.min' => 'The bet amount must be at least 5 Birr.',
            // 'selected_numbers.required' => 'At least one card must be selected.',
            // 'selected_numbers.array' => 'Selected numbers must be an array.',
            'selected_numbers.min' => 'At least one card must be selected.',
            'call_speed.required' => 'The call speed is required.',
            'call_speed.in' => 'The selected call speed is invalid.',
            'caller_language.required' => 'The caller language is required.',
            'caller_language.in' => 'The selected caller language is invalid.',
            'winning_pattern.required' => 'The winning pattern is required.',
            'winning_pattern.exists' => 'The selected winning pattern does not exist.',
        ];
    }
}
