<?php

use App\Helpers\CoreConstants;
use Illuminate\Support\Facades\Route;

if (env('APP_ENV') !== 'production') {
    Route::get('command/{cmd}', function ($cmd) {
        try {
            Artisan::call($cmd);

            return response()->json([
                'message' => 'Command successfully executed',
                'payload' => null,
                'status'  => CoreConstants::STATUS_CODE_SUCCESS
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    });
}

//log viewer
Route::get('/admin/system-logs', ['\Rap2hpoutre\LaravelLogViewer\LogViewerController', 'index']);


Route::get('/optimize', ['App\Http\Controllers\Admin\AdminController', 'optimize'])->name('optimize');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/{path?}', ['App\Http\Controllers\Admin\AdminController', 'app'])->where('path', '.*')->name('admin.app');
});


#region [frontend]

Route::get('/', ['App\Http\Controllers\Frontend\FrontendController', 'index'])->name('frontend');
Route::get('/pixel-tracker', ['App\Http\Controllers\Frontend\FrontendController', 'pixelTracker'])->name('pixel-tracker');

#endregion
