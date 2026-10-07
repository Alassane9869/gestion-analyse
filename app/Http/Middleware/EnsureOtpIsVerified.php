<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est connecté et a un code OTP en attente (nouveau compte non vérifié)
        if ($user && $user->hasPendingOtp() && !$request->routeIs('otp.*') && !$request->routeIs('logout')) {
            return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
