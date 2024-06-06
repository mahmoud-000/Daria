<?php

namespace Modules\Locale\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    app()->setLocale($request->cookie('locale', config('app.locale')));

    return $next($request);
  }
}
