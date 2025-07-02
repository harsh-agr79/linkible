<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PricingController;

Route::post('/contact', [ContactController::class, 'contact']);

Route::get('/pricings', [PricingController::class, 'getPricings']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
