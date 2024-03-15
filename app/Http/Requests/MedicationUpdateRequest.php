<?php

namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ApiFormRequest;

class MedicationUpdateRequest extends ApiFormRequest
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
            'description' => 'string|max:100',
            'quantity' => 'required|numeric',
            'slug' => 'nullable|string|max:120|unique:medications,slug,' . request()->id,
            'image' => 'nullable|image',
        ];
    }
}
