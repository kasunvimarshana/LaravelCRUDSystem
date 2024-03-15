<?php

namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiFormRequest;

class LoginRequest extends ApiFormRequest
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
            //
            'username'    => 'required|string|max:100',
            'password' => 'required|min:6'
        ];
    }
}
