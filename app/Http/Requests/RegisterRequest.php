<?php

namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiFormRequest;

class RegisterRequest extends ApiFormRequest
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
            'name'     => 'required|string|max:50',
            'username'    => 'required|string|max:100|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|string',
        ];
    }
}

