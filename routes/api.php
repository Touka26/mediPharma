<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SalesController;
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
    Route::post('/change_password/{id}', [PharmacistController::class, 'change_password']);
    Route::get('/showProfile/{id}', [PharmacistController::class, 'index']);
    Route::post('/forgetPassword', [ForgetPasswordController::class, 'forgetPassword']);
    Route::post('/resetPassword', [ResetPasswordController::class, 'resetPassword']);
    Route::get('/searchByName/{name}', [PharmacistController::class, 'searchByName']);
    Route::get('/searchByBarcode/{barcode}', [PharmacistController::class, 'searchByBarcode']);
    Route::get('/getBox/{id}', [PharmacistController::class, 'getBox']);
    Route::get('/showNotification/{id}', [PharmacistController::class, 'showNotification']);


});

Route::prefix('employee')->group(function () {
    Route::post('/add', [EmployeeController::class, 'store']);
    Route::post('/update/{id}', [EmployeeController::class, 'update']);
    Route::post('/addMonthSalary', [EmployeeController::class, 'addMonthSalary']);
    Route::get('/index', [EmployeeController::class, 'index']);
    Route::get('/show/{id}', [EmployeeController::class, 'show']);
    Route::get('/countOfEmployee', [EmployeeController::class, 'countOfEmployee']);
    Route::get('/displayMonth/{id}', [EmployeeController::class, 'displayMonth']);
    Route::delete('/destroy/{id}', [EmployeeController::class, 'destroy']);
});

Route::prefix('medicine')->group(function () {
    Route::post('/add', [MedicineController::class, 'store']);
    Route::post('/update/{id}', [MedicineController::class, 'update']);
    Route::get('/show/{id}', [MedicineController::class, 'show']);
    Route::get('/showMedicine/{id}', [MedicineController::class, 'showMedicine']);
    Route::get('/index', [MedicineController::class, 'index']);
    Route::get('/searchByBarcode/{barcode}', [MedicineController::class, 'searchByBarcode']);
    Route::get('/searchByTradeName/{trade}', [MedicineController::class, 'searchByTradeName']);

});

Route::prefix('product')->group(function () {
    Route::post('/add', [ProductController::class, 'store']);
    Route::post('/update/{id}', [ProductController::class, 'update']);
    Route::get('/searchByBarcode/{barcode}', [ProductController::class, 'searchByBarcode']);
    Route::get('/searchByName/{name}', [ProductController::class, 'searchByName']);
    Route::get('/show/{id}', [ProductController::class, 'show']);
    Route::get('/showProduct/{id}', [ProductController::class, 'showProduct']);
});

Route::prefix('purchases')->group(function () {
    Route::post('/add', [PurchasesController::class, 'storePurchases']);
    Route::post('/details', [PurchasesController::class, 'details']);
    Route::get('/index', [PurchasesController::class, 'index']);
    Route::get('/checkBarcode/{barcode}', [PurchasesController::class, 'checkBarcode']);
    Route::get('/detailsPurchases/{id}', [PurchasesController::class, 'detailsPurchases']);
    Route::get('/searchByDate/{date}', [PurchasesController::class, 'searchByDate']);


});

Route::prefix('sales')->group(function () {
    Route::post('/storeSales', [SalesController::class, 'storeSales']);
    Route::post('/storeBug', [SalesController::class, 'storeBug']);
    Route::post('/checkMedicine/{id}', [SalesController::class, 'checkMedicine']);
    Route::get('/showBug', [SalesController::class, 'showBug']);
    Route::get('/showAllSales', [SalesController::class, 'showAllSales']);
    Route::get('/confirmation', [SalesController::class, 'confirmation']);
    Route::get('/showSalesDetails/{id}', [SalesController::class, 'showSalesDetails']);
    Route::get('/diagram', [SalesController::class, 'diagram']);
    Route::get('/searchByDate/{date}', [SalesController::class, 'searchByDate']);
    Route::delete('/destroy/{id}', [SalesController::class, 'destroy']);
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/updateFCMToken/{id}', [AdminController::class, 'updateFCMToken']);
    Route::post('/sendAcceptNoti/{id}', [AdminController::class, 'sendAcceptNoti']);
    Route::post('/sendRejectNoti/{id}', [AdminController::class, 'sendRejectNoti']);
    Route::get('/getPharmacist', [AdminController::class, 'getPharmacist']);
    Route::get('/pharmacistByID/{id}', [AdminController::class, 'pharmacistByID']);

});



