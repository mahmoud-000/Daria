<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Http\Requests\ForgetPasswordRequest;

class ForgetPasswordAction extends Controller
{
  public function __invoke(ForgetPasswordRequest $request)
  {
    $response = $this->broker()->sendResetLink(
      $request->only('email')
    );

    return $response == Password::RESET_LINK_SENT
      ? $this->sendResetLinkResponse($request, $response)
      : $this->sendResetLinkFailedResponse($request, $response);
  }

  protected function broker()
  {
    return Password::broker();
  }

  protected function sendResetLinkResponse(Request $request, $response)
  {
    return $this->success(['message' => trans('auth::' . $response)]);
  }

  protected function sendResetLinkFailedResponse(Request $request, $response)
  {
    return $this->error(['message' => trans('auth::' . $response)]);
  }
}
