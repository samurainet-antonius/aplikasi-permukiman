<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateApi extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         return 'expired token';
    //     }
    // }

    public function handle($request, Closure $next, ...$guards)
    {

        // if (!$request->hasHeader('Authorization')) {
        //     abort(401, 'Unauthorized');
        // }

        $token =  JWTAuth::getToken();
        try {
            $user = JWTAuth::authenticate($token);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $next($request);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Token Invalid',
            ], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }


        return $next($request);
    }
}
