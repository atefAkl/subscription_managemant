<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        $user = auth()->user();

        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect based on user's actual role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة');
            } else {
                return redirect()->route('client.dashboard')
                    ->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة');
            }
        }

        return $next($request);
    }
}
