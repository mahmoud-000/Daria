<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Str;

class ResetPasswordAction extends Controller
{
  public function __invoke(ResetPasswordRequest $request)
  {
    $response = $this->broker()->reset(
      $this->credentails($request),
      function ($user, $password) {
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $user->save();
      }
    );

    return $response == Password::PASSWORD_RESET
      ? $this->sendResetLinkResponse($request, $response)
      : $this->sendResetLinkFailedResponse($request, $response);
  }

  protected function credentails(Request $request)
  {
    return $request->only('email', 'password', 'password_confirmation', 'token');
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
