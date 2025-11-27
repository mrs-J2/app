<?php

namespace App\Http\Middleware;
use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    $response = $next($request);

    // Log only important stuff like logins, changes, deletes
    $path = $request->path();
    if (str_contains($path, 'login') || str_contains($path, 'logout') || str_contains($path, 'users') && in_array($request->method(), ['POST', 'PUT', 'DELETE']) || str_contains($path, 'roles') || str_contains($path, 'courses') && str_contains($path, 'refuse') || str_contains($path, 'delete')) {
        Log::create([
            'action' => $request->method() . ' ' . $path,
            'user_id' => auth()->id() ?? null, // If logged in
            'date' => now(),
            'ip' => $request->ip(),
            'resource' => $path,
        ]);
    }

    return $response;
}
}
