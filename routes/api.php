<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\PolicyController;

Route::post('/contact', [ContactController::class, 'contact']);
Route::get('/contact-meta', [ContactController::class, 'contactMeta']);

Route::get('/pricings', [PricingController::class, 'getPricings']);

Route::get('/blogs', [BlogController::class, 'blogs']);

Route::get('/case-studies', [BlogController::class, 'caseStudies']);

Route::get('/blog/{slug}', [BlogController::class, 'show']);

Route::get('/faqs', [FAQController::class, 'getFaq']);

Route::get('policy', [PolicyController::class, 'policy']);

Route::get('terms', [PolicyController::class, 'terms']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
