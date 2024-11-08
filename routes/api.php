<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;
  
  
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
  
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('articles/search', [ArticleController::class, 'searchArticles']);
Route::get('user/preferences', [UserPreferenceController::class, 'getPreferences']);
Route::post('user/preferences', [UserPreferenceController::class, 'updatePreferences']);

// Route::middleware('auth:api')->group( function () {
//     Route::resource('products', ProductController::class);
// });