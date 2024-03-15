<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Facades\Log;
use App\Traits\ResponseTrait;

class ApiFormRequest extends FormRequest
{
    use ResponseTrait;

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->responseError((new ValidationException($validator))->errors(), 'Invalid form request', Response::HTTP_BAD_REQUEST)
        );
    }
}
