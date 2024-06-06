<?php

namespace App\Http\Controllers;

class ApplicationController extends Controller
{
    public function __invoke()
    {
        // Cache::forget('systemsettings');
        return  view('application');
    }
}
