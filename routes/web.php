<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\offerController;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\PrietoController;
use Illuminate\Support\Facades\Route;

// HOME
Route::get("/", [PrietoController::class, 'mostrar'])->name("home_prieto");

// AUTH routes are in auth.php (login, register, logout, etc.)

Route::middleware("auth")->group(function () {
    // CARRITO
    Route::get("/cartShow", [cartController::class, 'cartShow'])->name("cartShow");
    Route::post("/cartAdd/{id}", [cartController::class, 'cartAdd'])->name("cartAdd");
    Route::delete("/cartRemove/{id}", [cartController::class, 'cartRemove'])->name("cartRemove");
    Route::delete("/cartClear", [cartController::class, 'cartClear'])->name("cartClear");
    Route::post("/cartAddOne/{id}", [cartController::class, 'cartAddOne'])->name("cartAddOne");
    Route::post("/cartRemoveOne/{id}", [cartController::class, 'cartRemoveOne'])->name("cartRemoveOne");
    Route::post("/cartOrder", [cartController::class, 'cartOrder'])->name("cartOrder");

    // PEDIDOS
    Route::get("/ordersShow", [PrietoController::class, 'ordersShow'])->name("ordersShow");
});

// ADMIN ROUTES
Route::middleware(["auth", "isAdmin"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        Route::get("orders", [AdminController::class, 'ordersIndex'])->name("orders.index");
        Route::resource("products", productController::class);
        Route::resource("offers", offerController::class);
    });

require __DIR__ . '/auth.php';
