<?php

namespace App\Http\Middleware;

use App\Role\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()
                ->route('login');
        }

        if (auth()->user()->role !== RoleEnum::ADMIN->value) {
            return redirect()
                ->route('homepage');
        }

        return $next($request);
    }
}
