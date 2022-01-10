<?php

namespace App\Http\Middleware;

use Closure;

class Instructor
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
        $instructor = $request->user()->role->name === 'instructor';
        if (!$instructor) {
            return response()->json(['error' => 'This user must be instructor!', 401]);
        }
        return $next($request);
    }
}
