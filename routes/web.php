<?php

use App\Http\Controllers\CompanyAlertController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CrmActionsController;
use App\Http\Controllers\CrmTransactionController;
use App\Http\Controllers\EAlertController;
use App\Http\Controllers\ERequestController;
use App\Http\Controllers\MerchantAlertController;
use App\Http\Controllers\MerchantController;
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
Route::resource('crm-actions', CrmActionsController::class);
Route::resource('crm-transactions', CrmTransactionController::class);
// Route::resource('users', UsersController::class);
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/data', [UsersController::class, 'data'])->name('users.data');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::webhooks('EthocaAlertNotification', 'Ethoca-Alert-Notification');
Route::webhooks('CrmAction', 'CRM-Action');

Route::get('alerts/companies/data', [CompanyAlertController::class, 'data'])->name('alerts.companies.data');
Route::resource('alerts/companies', CompanyAlertController::class)->names(
    [
        'index' => 'alerts.companies.index',
        'show' => 'alerts.companies.show',
    ]
);

Route::get('alerts/merchants/{merchant}/data', [EAlertController::class, 'merchantData'])->name('alerts.merchant.data');
Route::get('alerts/merchants/data', [EAlertController::class, 'merchantsData'])->name('alerts.merchants.data');
Route::get('alerts/merchants/{merchant}', [EAlertController::class, 'merchant'])->name('alerts.merchants.show');
Route::get('alerts/merchants', [EAlertController::class, 'merchants'])->name('alerts.merchants.index');

Route::get('alerts/companies/{company}/data', [EAlertController::class, 'companyData'])->name('alerts.company.data');
Route::get('alerts/companies/data', [EAlertController::class, 'companiesData'])->name('alerts.companies.data');
Route::get('alerts/companies/{company}', [EAlertController::class, 'company'])->name('alerts.companies.show');
Route::get('alerts/companies', [EAlertController::class, 'companies'])->name('alerts.companies.index');

Route::resource('alerts', EAlertController::class);
Route::get('alerts-data', [EAlertController::class, 'data'])->name('alerts.data');

Route::get('companies/{company}/merchants', [CompanyController::class, 'merchants'])->name('companies.merchants');
Route::get('companies/{company}/merchants/data', [CompanyController::class, 'merchantsData'])->name('companies.merchants.data');
Route::resource('companies', CompanyController::class);



Route::resource('merchants', MerchantController::class);
