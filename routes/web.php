<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// User routes
Route::post('/user/create', [UserController::class, 'createUser']);
Route::post('/admin/create', [UserController::class, 'createAdmin']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user/{id}', [UserController::class, 'show']);

// Event Routes
Route::get('/events', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::post('/event/create', [EventController::class, 'create']);
Route::put('/event/update/{id}', [EventController::class, 'update']);
Route::delete('/event/delete/{id}', [EventController::class, 'delete']);

// Event Participant Routes
Route::get('/participants', [EventParticipantController::class, 'index']);
Route::get('/participant/{id}', [EventParticipantController::class, 'show']);
Route::delete('/participant/delete/{id}', [EventParticipantController::class, 'delete']);