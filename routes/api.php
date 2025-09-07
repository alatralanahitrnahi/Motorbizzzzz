<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InventoryController;
use App\Models\PurchaseOrder;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'apiIndex']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);

    // Purchase Order items
    Route::get('/purchase-orders/{poId}/items/{materialId}', [PurchaseOrderController::class, 'getItemQuantity']);
    
    // Single, clean route for all PO items
    Route::get('/purchase-orders/{purchaseOrder}/items', [PurchaseOrderController::class, 'items'])
        ->name('purchase-orders.items');

    // Inventory batch number generation
    Route::post('/inventory/generate-batch-number', [InventoryController::class, 'generateBatchNumber']);
});
