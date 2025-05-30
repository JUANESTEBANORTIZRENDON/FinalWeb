<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ArtistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isArtist()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No autorizado. Esta sección es solo para artistas.'], 403);
            }
            
            return redirect()->route('login')->with('error', 'Esta sección es solo para artistas.');
        }

        return $next($request);
    }
}
