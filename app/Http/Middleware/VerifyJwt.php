<?php

namespace App\Http\Middleware;

use CoreConstants;
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
                    'message' => 'Please Login to Continue',
                    'payload' => CoreConstants::TOKEN_INVALID,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ], CoreConstants::STATUS_CODE_ERROR);
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                if (Route::getRoutes()->match($request)->getName() === 'refresh-token') {
                    return $next($request);
                }

                return response()->json([
                    'message' => 'Please Login to Continue',
                    'payload' => CoreConstants::TOKEN_EXPIRED,
                    'status' => CoreConstants::STATUS_CODE_UNAUTHORIZED
                ], CoreConstants::STATUS_CODE_UNAUTHORIZED);
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->json([
                    'message' => 'Token is Blacklisted',
                    'payload' => CoreConstants::TOKEN_BLACKLISTED,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ], CoreConstants::STATUS_CODE_UNAUTHORIZED);
            } elseif ($th instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return response()->json([
                    'message' => 'Authorization token not found',
                    'payload' => CoreConstants::TOKEN_NOT_FOUND,
                    'status' => CoreConstants::STATUS_CODE_BAD_REQUEST
                ], CoreConstants::STATUS_CODE_BAD_REQUEST);
            } else {
                return response()->json([
                    'message' => 'Something went wrong',
                    'payload' => CoreConstants::TOKEN_INVALID,
                    'status' => CoreConstants::STATUS_CODE_ERROR
                ], CoreConstants::STATUS_CODE_ERROR);
            }
        }

        return $next($request);
    }
}
