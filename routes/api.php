<?php

use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'guest'], function () {
    // Authentication
    Route::post('login', [LoginController::class, 'login']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {

    Route::group([
        'prefix' => 'user',
        'as' => 'user.',
    ], function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });

        Route::post('/sendMail', [AuthController::class, 'sendMail'])
            ->name('sendMail');
    });

    Route::group([
        'prefix' => 'family',
        'as' => 'family.',
    ], function () {
        Route::get('/', [FamilyController::class, 'index'])
            ->name('index');
        Route::get('/tree', [FamilyController::class, 'tree'])
            ->name('tree');
        Route::post('/store', [FamilyController::class, 'store'])
            ->name('store');
        Route::delete('{id}/destroy', [FamilyController::class, 'destroy'])->name('destroy');
    });
}); // end auth:sanctum