<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotesController;
use Illuminate\Http\Request;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function($router){
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);
    Route::get('/userProfile',[UserController::class,'userProfile']);
    Route::post('/logout',[UserController::class,'logout']);

    Route::post('/createNotes',[NotesController::class,'createNotes']);
    Route::get('/displayNotes',[NotesController::class,'displayNotes']);

    Route::post('forgotpassword', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('resetpassword', [ForgotPasswordController::class, 'resetpassword']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
