<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clientecontroller;
use App\Http\Controllers\DocumentClienteController;
use App\Http\Controllers\DocumentComapnyController;
use App\Http\Controllers\Operationcontroller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clientes', Clientecontroller::class);
    Route::resource('operations', Operationcontroller::class);
    Route::resource('DocumentClientes', DocumentClienteController::class);
    Route::resource('DocumentComapnies', DocumentComapnyController::class);

    Route::get('/download/{id}', [DocumentComapnyController::class, 'download'])->name('DocumentComapnies.download');
    Route::get('/downloadClient/{id}', [Clientecontroller::class, 'downloadClient'])->name('clientes.download');
    Route::get('/downloadOperation/{id}', [Operationcontroller::class, 'downloadOperation'])->name('operations.download');
    Route::get('/searchoperation', [Operationcontroller::class, 'searchOperations'])->name('operations.search');
    Route::get('/searchcliente', [Clientecontroller::class, 'searchCliente'])->name('cliente.search');
    Route::get('/searchdocumentComapny', [DocumentComapnyController::class, 'searchDocumentComapny'])->name('DocumentComapnies.search');
    Route::post('getclientes', [Operationcontroller::class, 'getClient'])->name('get-client');
});


require __DIR__ . '/auth.php';
