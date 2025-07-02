<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

//Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('/events/create', [EventController::class, 'create'])->middleware('auth')->name('events.create');
