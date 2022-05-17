<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAjax
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->ajax()) {
            return response('page not found', 404);
        }

        return $next($request);
    }
}
