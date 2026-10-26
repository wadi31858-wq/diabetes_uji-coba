<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Silakan login sebagai administrator terlebih dahulu.'
                ], 401);
            }

            return redirect()->route('login')->withErrors([
                'auth' => 'Halaman Riwayat Pemeriksaan khusus untuk Admin yang telah terautentikasi. Silakan masuk terlebih dahulu.'
            ]);
        }

        $user = auth()->user();
        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki hak akses administrator.'
                ], 403);
            }

            return redirect()->route('home')->with('error', 'Anda tidak memiliki izin akses ke halaman administrator.');
        }

        return $next($request);
    }
}
