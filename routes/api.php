<?php

use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LabelController;
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
    
    Route::post('forgotpassword', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('resetpassword', [ForgotPasswordController::class, 'resetpassword']);

    Route::post('/createNotes',[NotesController::class,'createNotes']);
    Route::get('/displayNoteById',[NotesController::class,'displayNoteById']);
    Route::post('/updateNoteById',[NotesController::class,'updateNoteById']);
    Route::post('/deleteNoteById',[NotesController::class,'deleteNoteById']);
    Route::post('/pinNoteById',[NotesController::class,'pinNoteById']);
    Route::get('/getAllPinnedNotes',[NotesController::class,'getAllPinnedNotes']);
    Route::post('/archiveNoteById',[NotesController::class,'archiveNoteById']);
    Route::get('/getAllArchiveNotes',[NotesController::class,'getAllArchiveNotes']);
    Route::post('/colourNoteById',[NotesController::class,'colourNoteById']);
    Route::post('/searchAllNotes',[NotesController::class,'searchAllNotes']);

    Route::post('/createLabel',[LabelController::class,'createLabel']);
    Route::get('/displayLabelById',[LabelController::class,'displayLabelById']);
    Route::post('/updateLabelById',[LabelController::class,'updateLabelById']);
    Route::post('/deleteLabelById',[LabelController::class,'deleteLabelById']);
    Route::post('/addLabelByNoteId',[LabelController::class,'addLabelByNoteId']);
    Route::post('/deleteNoteLabel',[LabelController::class,'deleteNoteLabel']);

    Route::post('/addCollaboratorByNoteId',[CollaboratorController::class,'addCollaboratorByNoteId']);
    Route::post('/updateNoteByCollaborator',[CollaboratorController::class,'updateNoteByCollaborator']);
    Route::post('/removeCollaborator',[CollaboratorController::class,'removeCollaborator']);
    Route::get('/getAllCollaborators',[CollaboratorController::class,'getAllCollaborators']);




});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
