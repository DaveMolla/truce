<?php

// app/Http/Requests/PhoneLoginRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PhoneLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Get the credentials for the authentication attempt.
     *
     * @return array
     */
    public function credentials()
    {
        Log::info('Login Request Data', $this->only('phone', 'password')); // Add this line for debugging
        return $this->only('phone', 'password');
    }
}
