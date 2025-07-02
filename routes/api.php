<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'contact']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
