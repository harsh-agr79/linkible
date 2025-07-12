<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\ApiKey;

Route::post('/contact', [ContactController::class, 'contact']);
Route::get('/contact-meta', [ContactController::class, 'contactMeta']);

Route::get('/pricings', [PricingController::class, 'getPricings']);

Route::get('/blogs', [BlogController::class, 'blogs']);

Route::get('/case-studies', [BlogController::class, 'caseStudies']);

Route::get('/blog/{slug}', [BlogController::class, 'show']);

Route::get('/faqs', [FAQController::class, 'getFaq']);

Route::get('/policy', [PolicyController::class, 'policy']);

Route::get('/terms', [PolicyController::class, 'terms']);

Route::get('/links', [LinkController::class, 'getLinksList']);
Route::get('/link/{id}', [LinkController::class, 'getLinkData']);

Route::post('/blogs/{slug}/increment-view', [BlogController::class, 'incrementView']);

Route::get('/homepage', [HomePageController::class, 'homepage']);

Route::get('/about', [AboutController::class, 'getAboutUs']);

Route::get('/industries', [IndustryController::class, 'getIndustriesList']);
Route::get('/industry/{id}', [IndustryController::class, 'getIndustryData']);

Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook']);

Route::middleware(ApiKey::class)->group(function () { 
    Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
    Route::get('/order-successful/{id}', [PaymentController::class, 'getOrderDetails']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
