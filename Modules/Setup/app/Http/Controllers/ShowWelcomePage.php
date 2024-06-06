<?php

namespace Modules\Setup\Http\Controllers;

use Illuminate\Contracts\View\View;

class ShowWelcomePage {
  public function __invoke(): View 
  {
    return view('setup::setup.welcome', ['title' => trans('setup::setup.welcome')]);
  }
}