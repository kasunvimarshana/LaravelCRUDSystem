<?php

namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiFormRequest;

class MedicationCreateRequest extends ApiFormRequest
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
            'name' => 'required|string|max:100',
            'description' => 'string|max:100',
            'quantity' => 'required|numeric',
            'slug' => 'nullable|string|unique:medications|max:120',
            'image' => 'nullable|image',
        ];
    }
}
