<?php

use Illuminate\Support\Facades\Route;

if (env('APP_ENV') !== 'production') {
    Route::get('artisan/{cmd}', function ($cmd) {
        try {
            Artisan::call($cmd);

            return response()->json([
                'message' => 'Command successfully executed',
                'payload' => null,
                'status'  => Constants::STATUS_CODE_SUCCESS
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    });
}

//log viewer
Route::get('/admin/system-logs', ['\Rap2hpoutre\LaravelLogViewer\LogViewerController', 'index']);

Route::group(['prefix' => 'admin'], function() {
    Route::get('/{path?}', ['App\Http\Controllers\Admin\AdminController', 'app'])->where('path', '.*')->name('admin.app');
});