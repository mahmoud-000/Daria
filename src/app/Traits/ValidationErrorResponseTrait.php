<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

trait ValidationErrorResponseTrait
{
  public function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(
      response()->json([
        'success' => false,
        'payload' => $validator->errors()
      ], Response::HTTP_UNPROCESSABLE_ENTITY)
    );
  }

  protected function failedAuthorization()
  {
    throw new HttpResponseException(
      response()->json([
        'success' => false,
        'message' => __('permission::messages.gate_denies')//__('auth::auth.action_unauthorized')
      ], Response::HTTP_FORBIDDEN)
    );
  }
}
