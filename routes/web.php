<?php

use App\Http\Controllers\Auth\ZohoAuthController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/auth/zoho', [ZohoAuthController::class, 'redirectToZoho'])->name('auth.zoho');
Route::get('/auth/zoho/callback', [ZohoAuthController::class, 'handleZohoCallback']);

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations');
    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies');
    Route::get('/chart-of-accounts', [ChartOfAccountController::class, 'index'])->name('chart.accounts');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses');
    Route::get('/synclogs', function () {
        return Inertia::render('SyncLog');
    })->name('synclogs');

    Route::get('/expenses/{id}/update', [ExpenseController::class, 'edit'])->name('expenses.update');
    Route::post('/expenses/{id}/update/receipt', [ExpenseController::class, 'update'])->name('expenses.update.receipt');

    Route::post('/sync-organizations', [OrganizationController::class, 'syncOrganizations'])->name('sync.orgs');
    Route::post('/sync-currencies', [CurrencyController::class, 'syncCurrencies'])->name('sync.currencies');
    Route::post('/sync-chart-of-accounts', [ChartOfAccountController::class, 'syncChartOfAccounts'])->name('sync.chart.accounts');
    Route::post('/sync-contacts', [ContactController::class, 'syncContacts'])->name('sync.contacts');
    Route::post('/sync-expenses', [ExpenseController::class, 'syncExpenses'])->name('sync.expenses');
});
