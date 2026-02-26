<?php

use App\Domains\Auth\Http\Controllers\AuthController;
use App\Domains\Inventory\Http\Controllers\InventoryItemController;
use App\Domains\Transfer\Http\Controllers\StockTransferController;
use App\Domains\Warehouse\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('inventory', InventoryItemController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::get('/warehouses/{warehouse}/inventory', [WarehouseController::class, 'inventory']);
    Route::apiResource('stock-transfers', StockTransferController::class)->only(['index', 'store', 'show']);
});
