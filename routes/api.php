<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
// use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\CustomerTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => ['cors', 'json.response']], function () {
//     //
// });


Route::group([
    'prefix' => 'v1',
    'as' => 'api.',
    'namespace' => '',
], function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('register', [RegisterController::class, 'register'])->name('register');
});

Route::group([
    'prefix' => 'v1',
    'as' => 'api.',
    'namespace' => '',
    'middleware' => ['auth:api'],
], function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('logout', [ProfileController::class, 'logout']);
    // Customers
    Route::post('customers/store', [CustomerController::class, 'store']);
    Route::put('customers/update/{id}', [CustomerController::class, 'update']);
    Route::get('customers/show/{id}', [CustomerController::class, 'show']);
    Route::get('customers/filter', [CustomerController::class, 'filter']);
    Route::delete('customers/flag-as-deleted/{id}', [CustomerController::class, 'flagAsDeleted']);
    Route::delete('customers/permanently-delete-records/{id}', [CustomerController::class, 'destroyPermanently']);
    // Medications
    Route::post('medications/store', [MedicationController::class, 'store']);
    Route::put('medications/update/{id}', [MedicationController::class, 'update']);
    Route::get('medications/show/{id}', [MedicationController::class, 'show']);
    Route::get('medications/filter', [MedicationController::class, 'filter']);
    Route::delete('medications/flag-as-deleted/{id}', [MedicationController::class, 'flagAsDeleted']);
    Route::delete('medications/permanently-delete-records/{id}', [MedicationController::class, 'destroyPermanently']);
    // CustomerTransactions
    Route::post('customer-medication-purchase/store', [CustomerTransactionController::class, 'purchaseMedication']);
    Route::post('customer-medication-return/store', [CustomerTransactionController::class, 'returnMedication']);
});
