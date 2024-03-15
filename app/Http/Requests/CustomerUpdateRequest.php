<?php

namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiFormRequest;

class CustomerUpdateRequest extends ApiFormRequest
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
        Log::debug("ID:: " . request()->id);
        return [
            //
            'name' => 'required|string|max:100',
            'address' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:120|unique:customers,email,' . request()->id,
        ];
    }
}

