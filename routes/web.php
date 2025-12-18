<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aplikasi Manajemen Gudang Produksi - Mari Partner
|
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Redirect root to dashboard
    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kepegawaian
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
    Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Akuntansi
    Route::resource('chart-of-accounts', \App\Http\Controllers\ChartOfAccountController::class);
    Route::resource('journals', \App\Http\Controllers\JournalController::class);
    Route::post('journals/{journal}/post', [\App\Http\Controllers\JournalController::class, 'post'])->name('journals.post');
    Route::get('ledger', [\App\Http\Controllers\LedgerController::class, 'index'])->name('ledger.index');
    Route::get('ledger/export/excel', [\App\Http\Controllers\LedgerController::class, 'exportExcel'])->name('ledger.export.excel');
    Route::get('ledger/export/pdf', [\App\Http\Controllers\LedgerController::class, 'exportPdf'])->name('ledger.export.pdf');
    Route::get('ledger/{account}', [\App\Http\Controllers\LedgerController::class, 'show'])->name('ledger.show');
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/balance-sheet', [\App\Http\Controllers\ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('reports/balance-sheet/export', [\App\Http\Controllers\ReportController::class, 'exportBalanceSheet'])->name('reports.balance-sheet.export');
    Route::get('reports/income-statement', [\App\Http\Controllers\ReportController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('reports/income-statement/export', [\App\Http\Controllers\ReportController::class, 'exportIncomeStatement'])->name('reports.income-statement.export');
    Route::get('reports/cash-flow', [\App\Http\Controllers\ReportController::class, 'cashFlow'])->name('reports.cash-flow');
    Route::get('reports/cash-flow/export', [\App\Http\Controllers\ReportController::class, 'exportCashFlow'])->name('reports.cash-flow.export');

    // Master Data
    Route::resource('consumers', \App\Http\Controllers\ConsumerController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('units', \App\Http\Controllers\UnitController::class);
    Route::resource('price-types', \App\Http\Controllers\PriceTypeController::class);
    Route::resource('warehouses', \App\Http\Controllers\WarehouseController::class);
    Route::resource('installment-types', \App\Http\Controllers\InstallmentTypeController::class);

    // Warehouse
    Route::resource('materials', \App\Http\Controllers\MaterialController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('stocks', \App\Http\Controllers\StockController::class);
    Route::post('stocks/{stock}/adjust', [\App\Http\Controllers\StockController::class, 'adjust'])->name('stocks.adjust');

    // Manufaktur
    Route::resource('productions', \App\Http\Controllers\ProductionController::class);
    Route::post('productions/{production}/start', [\App\Http\Controllers\ProductionController::class, 'start'])->name('productions.start');
    Route::post('productions/{production}/complete', [\App\Http\Controllers\ProductionController::class, 'complete'])->name('productions.complete');
    Route::resource('production-teams', \App\Http\Controllers\ProductionTeamController::class);

    // Ekspedisi
    Route::resource('delivery-notes', \App\Http\Controllers\DeliveryNoteController::class);
    Route::post('delivery-notes/{deliveryNote}/ship', [\App\Http\Controllers\DeliveryNoteController::class, 'ship'])->name('delivery-notes.ship');
    Route::post('delivery-notes/{deliveryNote}/deliver', [\App\Http\Controllers\DeliveryNoteController::class, 'deliver'])->name('delivery-notes.deliver');
    Route::resource('returns', \App\Http\Controllers\ReturnsController::class);
    Route::post('returns/{return}/approve', [\App\Http\Controllers\ReturnsController::class, 'approve'])->name('returns.approve');
    Route::post('returns/{return}/process', [\App\Http\Controllers\ReturnsController::class, 'process'])->name('returns.process');

    // Transaksi
    Route::resource('sales', \App\Http\Controllers\SalesTransactionController::class);
    Route::post('sales/{sale}/confirm', [\App\Http\Controllers\SalesTransactionController::class, 'confirm'])->name('sales.confirm');
    Route::post('sales/{sale}/payment', [\App\Http\Controllers\SalesTransactionController::class, 'addPayment'])->name('sales.payment');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::post('expenses/{expense}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])->name('expenses.approve');

    // Perhitungan
    Route::get('calculator/pph21', [\App\Http\Controllers\CalculatorController::class, 'pph21'])->name('calculator.pph21');
    Route::post('calculator/pph21', [\App\Http\Controllers\CalculatorController::class, 'calculatePph21'])->name('calculator.pph21.calculate');

    // Settings
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Backup
    Route::get('backups', [\App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
    Route::post('backups', [\App\Http\Controllers\BackupController::class, 'create'])->name('backups.create');
    Route::get('backups/{backup}/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
    Route::delete('backups/{backup}', [\App\Http\Controllers\BackupController::class, 'destroy'])->name('backups.destroy');
});
