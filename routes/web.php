<?php

use App\Http\Controllers\CrmActionsController;
use App\Http\Controllers\CrmTransactionController;
use App\Http\Controllers\EAlertController;
use App\Http\Controllers\ERequestController;
use App\Http\Controllers\UsersController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response(['message' => 'hello']);
});
Route::resource('requests', ERequestController::class);
Route::resource('alerts', EAlertController::class);
Route::get('alerts-data', [EAlertController::class,'data'])->name('alerts.data');
Route::resource('crm-actions', CrmActionsController::class);
Route::resource('crm-transactions', CrmTransactionController::class);
// Route::resource('users', UsersController::class);
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/data', [UsersController::class, 'data'])->name('users.data');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::webhooks(env('ETHOCA_WEBHOOK_ENDPOINT', 'EthocaAlertNotification'));
