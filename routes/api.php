<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ResetPasswordController;
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
    Route::post('/login', [PharmacistController::class, 'login']);
    Route::delete('/delete/{id}', [PharmacistController::class, 'deleteAccount']);
    Route::post('/update/{id}', [PharmacistController::class, 'update']);
    Route::get('/showProfile/{id}', [PharmacistController::class, 'index']);
    Route::post('/forgetPassword', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword']);

});

Route::prefix('employee')->group(function () {
    Route::post('/add', [EmployeeController::class, 'store']);
    Route::post('/update/{id}', [EmployeeController::class, 'update']);
    Route::post('/addMonthSalary', [EmployeeController::class, 'addMonthSalary']);
    Route::get('/index', [EmployeeController::class, 'index']);
    Route::get('/show', [EmployeeController::class, 'show']);
    Route::get('/countOfEmployee', [EmployeeController::class, 'countOfEmployee']);
    Route::get('/displayMonth/{id}', [EmployeeController::class, 'displayMonth']);
    Route::delete('/destroy/{id}', [EmployeeController::class, 'destroy']);
});
