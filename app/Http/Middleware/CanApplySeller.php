<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanApplySeller
{
    public function handle(Request $request, Closure $next): Response
    {
        $status = $request->user()->seller_status;

        if ($status === 'pending') {
            return redirect()->route('profile');
        }

        if ($status === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        return $next($request);
    }
}
