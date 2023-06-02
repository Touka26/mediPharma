<?php

use App\Http\Controllers\PharmacistController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('pharmacist')->group(function () {
    Route::post('/register', [PharmacistController::class, 'register']);
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/login', [PharmacistController::class, 'login']);
        Route::post('/logout', [PharmacistController::class, 'logout']);
    });
    Route::delete('/delete/{id}', [PharmacistController::class, 'deleteAccount']);
    Route::post('/update/{id}', [PharmacistController::class, 'update']);
    Route::get('/showProfile/{id}', [PharmacistController::class, 'index']);


});
