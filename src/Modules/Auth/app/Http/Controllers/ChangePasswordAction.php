<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\User\Models\User;

class ChangePasswordAction extends Controller
{
  public function __invoke(ChangePasswordRequest $request)
  {
    $user = User::where('email', auth()->user()->email)->first();

    if (!Hash::check($request->current_password, $user->password)) {
      return $this->error([
        'message' => trans('auth::auth.current_password_not_correct')
      ], Response::HTTP_NOT_FOUND);
    }

    $updatad = $user->update([
      'password' => $request->new_password
    ]);

    if ($updatad) {
      return $this->success([
        'message' => trans('auth::auth.change_password_success')
      ], Response::HTTP_OK);
    }
  }
}
