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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
