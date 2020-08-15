<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifiedAccount extends EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
      if (! $request->user() ||
          ($request->user() instanceof MustVerifyEmail &&
          ! $request->user()->hasVerifiedEmail())) {
          return abort(403, 'Your email address is not verified.');
      }

      return $next($request);
    }
}
