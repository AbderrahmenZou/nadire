<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\API\ClienteController;
use App\Http\Controllers\API\OperationController;

use App\Http\Controllers\API\DocumentComapnyController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(["namespace" => "API"], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware([EnsureTokenIsValid::class])->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });


    Route::post('/clientes/index',  [ClienteController::class, 'index']);
    Route::post('/clientes/store',  [ClienteController::class, 'store']);
    Route::post('/clientes/update',  [ClienteController::class, 'update']);
    Route::post('/clientes/delete',  [ClienteController::class, 'delete']);
    Route::post('/clientes/getClientByid',  [ClienteController::class, 'getClientByid']);
    Route::post('/clientes/searchcliente',  [ClienteController::class, 'searchCliente']);
    Route::post('/clientes/downloadClient',  [ClienteController::class, 'downloadClient']);


    Route::post('/operations/index',  [OperationController::class, 'index']);
    Route::post('/operations/store',  [OperationController::class, 'store']);
    Route::post('/operations/update',  [OperationController::class, 'update']);
    Route::post('/operations/delete',  [OperationController::class, 'delete']);
    Route::post('/operations/searchOperations',  [OperationController::class, 'searchOperations']);
    Route::post('/operations/downloadOperation',  [OperationController::class, 'downloadOperation']);

    Route::get('/Document_Comapny/index',  [DocumentComapnyController::class, 'index']);
    Route::post('/Document_Comapny/store',  [DocumentComapnyController::class, 'store']);
    Route::post('Document_Comapny/show',  [DocumentComapnyController::class, 'show']);
    Route::post('/Document_Comapny/update',  [DocumentComapnyController::class, 'update']);
    Route::post('/Document_Comapny/destroy',  [DocumentComapnyController::class, 'destroy']);
    Route::post('/Document_Comapny/searchDocumentComapny',  [DocumentComapnyController::class, 'searchDocumentComapny']);
    Route::post('/Document_Comapny/download',  [DocumentComapnyController::class, 'download']);
});
