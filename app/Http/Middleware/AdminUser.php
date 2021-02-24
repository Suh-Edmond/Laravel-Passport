<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminUser
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
        //   dd($request->all())
        if ($request->user()->tokenCan(['delete', 'add_product'])) {
            return $next($request);
        } else {
            $message = "Permission denied";
            return response()->json(["message" => $message, "status" => 401]);
        }
    }
}
