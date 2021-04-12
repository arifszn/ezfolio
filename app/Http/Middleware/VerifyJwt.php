<?php

namespace App\Http\Middleware;

use Constants;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Route;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJwt
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
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $th) {
            if ($th instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'message' => 'Token is Invalid',
                    'payload' => Constants::TOKEN_INVALID,
                    'status' => Constants::STATUS_CODE_ERROR
                ], Constants::STATUS_CODE_ERROR);
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                if (Route::getRoutes()->match($request)->getName() === 'refresh-token') {
                    return $next($request);
                }

                return response()->json([
                    'message' => 'Token is Expired',
                    'payload' => Constants::TOKEN_EXPIRED,
                    'status' => Constants::STATUS_CODE_UNAUTHORIZED
                ], Constants::STATUS_CODE_UNAUTHORIZED);
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json([
                    'message' => 'Token is Blacklisted',
                    'payload' => Constants::TOKEN_BLACKLISTED,
                    'status' => Constants::STATUS_CODE_ERROR
                ], Constants::STATUS_CODE_UNAUTHORIZED);
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json([
                    'message' => 'Authorization token not found',
                    'payload' => Constants::TOKEN_NOT_FOUND,
                    'status' => Constants::STATUS_CODE_BAD_REQUEST
                ], Constants::STATUS_CODE_BAD_REQUEST);
            } else {
                return response()->json([
                    'message' => 'Something went wrong',
                    'payload' => Constants::TOKEN_INVALID,
                    'status' => Constants::STATUS_CODE_ERROR
                ], Constants::STATUS_CODE_ERROR);
            }
        }

        return $next($request);
    }
}
