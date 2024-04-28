<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\API\DocumentClienteController;
use App\Http\Controllers\API\ClienteController;
use App\Http\Controllers\API\OperationController;
use App\Http\Controllers\API\CategoryController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(["namespace" => "API"], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware([EnsureTokenIsValid::class])->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        
        Route::post('/clientes/index',  [ClienteController::class , 'index']);
        Route::post('/create',  [CategoryController::class , 'create']);
        Route::post('/getAll',  [CategoryController::class , 'getAll']);
        Route::post('/update',  [CategoryController::class , 'update']);
        Route::post('/delete',  [CategoryController::class , 'delete']);
        Route::post('/getcategByid',  [CategoryController::class , 'getcategByid']);

        
    });
});

    Route::middleware('auth')->group(function () {

        
        Route::post('/clientes/store',  [ClienteController::class , 'store']);
        Route::post('/clientes/update',  [ClienteController::class , 'update']);
        Route::post('/clientes/delete',  [ClienteController::class , 'delete']);
        Route::post('/clientes/getClientByid',  [ClienteController::class , 'getClientByid']);
        Route::post('/clientes/searchcliente',  [ClienteController::class , 'searchCliente']);
        Route::post('/clientes/downloadClient',  [ClienteController::class , 'downloadClient']);
        
    });

    Route::middleware('auth')->group(function () {

        Route::post('/operations/index',  [OperationController::class , 'index']);
        Route::post('/operations/create',  [OperationController::class , 'store']);
        Route::post('/operations/update',  [OperationController::class , 'update']);
        Route::post('/operations/delete',  [OperationController::class , 'delete']);
        Route::post('/operations/getOperationByid',  [OperationController::class , 'getOperationByid']);
    });

    Route::middleware('auth')->group(function () {

        Route::post('/DocumentClientes/index',  [DocumentClienteController::class , 'index']);
        Route::post('/DocumentClientes/create',  [DocumentClienteController::class , 'create']);
        Route::post('/DocumentClientes/update',  [DocumentClienteController::class , 'update']);
        Route::post('/DocumentClientes/delete',  [DocumentClienteController::class , 'delete']);
        Route::post('/DocumentClientes/getDocumentByid',  [DocumentClienteController::class , 'getDocumentByid']);
        Route::post('/DocumentClientes/searchDocumentCliente',  [DocumentClienteController::class , 'searchDocumentCliente']);
        Route::post('/DocumentClientes/downloadDocumentCliente',  [DocumentClienteController::class , 'downloadDocumentCliente']);
    });

// Route::resource('clientes', ClienteController::class);



