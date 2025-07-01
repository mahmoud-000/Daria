<?php

namespace Modules\Setup\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Modules\Permission\Models\Permission;
use Modules\User\Models\User;

class SaveAccount
{
  public function __invoke(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'username' => ['required', 'string', 'min:8', 'max:100'],
      'email' => ['required', 'email'],
      'password' => ['required', 'confirmed', Password::min(8)
        ->mixedCase()
        ->letters()
        ->numbers()
        ->symbols()
        ->uncompromised()]
    ]);

    $user = User::create($validated + [
      'is_owner' => true,
      'is_active' => true,
    ]);

    // $permissions = Permission::all();
    // $user->permissions()->attach($permissions);

    return redirect()->route('setup.complete');
  }
}
