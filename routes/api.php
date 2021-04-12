<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/status', function () {
        return response()->json([
            'message' => 'Running',
            'payload' => null,
            'status'  => Constants::STATUS_CODE_SUCCESS
        ]);
    });

    //admin auth
    Route::group(['prefix' => 'admin', 'middleware' => ['throttle:30,1']], function () { //max 30 request in i min
        Route::post('/login', ['App\Http\Controllers\Admin\Api\AdminController', 'login']);
        Route::post('/forget-password', ['App\Http\Controllers\Admin\Api\AdminController', 'forgetPassword']);
        Route::post('/reset-password', ['App\Http\Controllers\Admin\Api\AdminController', 'resetPassword']);

        Route::group(['middleware' => ['jwt.verify']], function () {
            Route::post('/refresh-token', ['App\Http\Controllers\Admin\Api\AdminController', 'refreshToken'])->name('refresh-token');

            Route::get('/me', ['App\Http\Controllers\Admin\Api\AdminController', 'me']);

            Route::match(['get', 'post'], '/login-credentials', ['App\Http\Controllers\Admin\Api\AdminController', 'loginCredentials']);

            Route::match(['get', 'post'], '/settings', ['App\Http\Controllers\Admin\Api\SettingController', 'index']);

            Route::match(['post', 'delete'], '/logo', ['App\Http\Controllers\Admin\Api\SettingController', 'logo']);

            Route::match(['post', 'delete'], '/favicon', ['App\Http\Controllers\Admin\Api\SettingController', 'favicon']);

            Route::post('/mail-settings', ['App\Http\Controllers\Admin\Api\SettingController', 'storeMailSettings']);

            Route::match(['get', 'post'], '/portfolio-configs', ['App\Http\Controllers\Admin\Api\PortfolioController', 'index']);
            
            Route::match(['get', 'post'], '/about', ['App\Http\Controllers\Admin\Api\PortfolioController', 'about']);

            Route::post('/seo', ['App\Http\Controllers\Admin\Api\PortfolioController', 'seo']);

            Route::match(['post', 'delete'], '/avatar', ['App\Http\Controllers\Admin\Api\PortfolioController', 'avatar']);

            Route::match(['post', 'delete'], '/cv', ['App\Http\Controllers\Admin\Api\PortfolioController', 'cv']);

            Route::match(['post', 'delete'], '/cover', ['App\Http\Controllers\Admin\Api\PortfolioController', 'cover']);

            Route::get('/skills', ['App\Http\Controllers\Admin\Api\SkillController', 'index']);
            Route::post('/skills', ['App\Http\Controllers\Admin\Api\SkillController', 'store']);
            Route::get('/skills/{id}', ['App\Http\Controllers\Admin\Api\SkillController', 'show']);
            Route::put('/skills/{id}', ['App\Http\Controllers\Admin\Api\SkillController', 'update']);
            Route::delete('/skills', ['App\Http\Controllers\Admin\Api\SkillController', 'destroy']);

            Route::get('/education', ['App\Http\Controllers\Admin\Api\EducationController', 'index']);
            Route::post('/education', ['App\Http\Controllers\Admin\Api\EducationController', 'store']);
            Route::get('/education/{id}', ['App\Http\Controllers\Admin\Api\EducationController', 'show']);
            Route::put('/education/{id}', ['App\Http\Controllers\Admin\Api\EducationController', 'update']);
            Route::delete('/education', ['App\Http\Controllers\Admin\Api\EducationController', 'destroy']);
        });
    });
});