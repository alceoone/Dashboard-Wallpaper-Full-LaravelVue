<?php

namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
 
class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'success'    => false,
                    'message'    => 'Token is Invalid',
                    'error'      => 'invalid',
                    ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                $newToken = JWTAuth::parseToken()->refresh();
                return response()->json([
                    
                    'success'    => false,
                    'message'    => 'Token is Expired',
                    'error'      => 'Token Refresh',
                    'accessToken' => false,
                    'newToken' => $newToken,
                ]);
            }else{
                return response()->json([
                    'success'    => false,
                    'message'    => 'Authorization Token not found',
                    'error'      => 'auth',
                    ]);
            }
        }
        return $next($request);
    }
}