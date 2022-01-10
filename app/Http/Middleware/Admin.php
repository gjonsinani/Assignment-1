<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin = $request->user()->role->name === 'admin';
        if (!$admin) {
            return response()->json(['error' => 'This user must be admin!', 401]);
        }
        return $next($request);
    }
}
