<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق إذا كان المستخدم مسجل دخول وهو مدير
        if (Auth::check() && Auth::user()->isAdmin) {
            return $next($request);
        }

        // إعادة توجيه المستخدم إذا لم يكن مديرًا
        return redirect('/')->with('error', 'ليس لديك صلاحية الوصول إلى لوحة التحكم.');
    }
}