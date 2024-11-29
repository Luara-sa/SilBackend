<?php
/**
 * Created by Omar Matter.
 * Date: 29/11/2024
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $response = response()->jsonResponse(false, $validator->errors()->first(), [], 422);
        throw new ValidationException($validator , $response , false);
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('messages.unauthorized'));
    }
}
