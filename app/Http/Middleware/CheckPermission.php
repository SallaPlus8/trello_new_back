<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {

        $user = Auth::user();
       
        if ($user->hasPermission($permission) || $user->super_admin){

            return $next($request);
        }
        return response()->json([
            'success' => false,
            'message' => 'unauthorized',
        ]);
      
    }
}
