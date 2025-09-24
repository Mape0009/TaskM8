<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::get('events-get', [AppController::class, 'eventGet']);
Route::get('event-participants-get', [AppController::class, 'eventParticipantGet']);
Route::get('shifts-get', [AppController::class, 'shiftGet']);
