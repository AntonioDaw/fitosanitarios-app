<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }


        if ($user->role !== 'admin') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return $next($request);
    }
}

