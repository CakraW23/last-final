<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// client
Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])->name('client.dashboard');

    Route::get('/topuppage', fn() => view('siswa.topup'))->name('client.topup.page');
    Route::post('/topup', [ClientController::class, 'topup'])->name('client.topup');

    Route::get('/withdrawpage', fn() => view('siswa.withdraw'))->name('client.withdraw.page');
    Route::post('/withdraw', [ClientController::class, 'withdraw'])->name('client.withdraw');

    Route::get('/transferpage', fn() => view('siswa.transfer'))->name('client.transfer.page');
    Route::post('/transfer', [ClientController::class, 'transfer'])->name('client.transfer');
});



// bank
Route::middleware(['auth', 'verified', 'role:bank'])->prefix('bank')->name('bank.')->group(function () {
    Route::get('/dashboard', [BankController::class, 'index'])->name('dashboard');

    Route::get('/createuser', [BankController::class, 'createPage'])->name('Createuser');
    Route::post('/createuser', [BankController::class, 'store'])->name('createuser');

    Route::put('/approve/{id}', [BankController::class, 'approve'])->name('approve');
    Route::put('/reject/{id}', [BankController::class, 'reject'])->name('reject');
    Route::post('/topup', [BankController::class, 'topup'])->name('topup');
    Route::post('/withdraw', [BankController::class, 'withdraw'])->name('withdraw');
});


// admin
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/createuser', [AdminController::class, 'createPage'])->name('Createuser');
    Route::post('/createuser', [AdminController::class, 'store'])->name('createuser');

    Route::get('/users/{user}/edit', [AdminController::class, 'editPage'])->name('Edituser');
    Route::put('/users/{user}/update', [AdminController::class, 'update'])->name('edituser');

    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('deleteuser');
});


require __DIR__.'/auth.php';
