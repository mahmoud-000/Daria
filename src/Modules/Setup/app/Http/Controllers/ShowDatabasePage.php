<?php

namespace Modules\Setup\Http\Controllers;

use Illuminate\Contracts\View\View;

class ShowDatabasePage {
  public function __invoke(): View 
  {
    return view('setup::setup.database', ['title' => trans('setup::setup.database_configuration')]);
  }
}