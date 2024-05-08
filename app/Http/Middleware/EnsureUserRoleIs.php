<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRoleIs
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        for ($i = 0; $i < count($roles); $i++) {
            if ($request->user()->hasRole($roles[$i])) {
                return $next($request);
            }
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }

}
