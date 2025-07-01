<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogoutAction extends Controller
{
  public function __invoke(Request $request)
  {
    if (!$request->user()) {
      return $this->error(['message' => trans('auth::auth.no_auth_user')]);
    }
    $request->user()->currentAccessToken()->delete();
    $request->user()->tokens()->delete();
    return $this->success([
      'message' => trans('auth::auth.logout', ['user' => auth()->user()->username])
    ], Response::HTTP_OK);
  }
}
