<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->input('token');

            if ($token) {
                $user = JWTAuth::parseToken()->authenticate();
            }
        } catch(\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['msg' => 'token is invalid']);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->json(['msg' => 'token is expired']);
            } else {
                return response()->json(['msg' => 'another exception']);
            }
        }
        
        return $next($request);
    }
}


