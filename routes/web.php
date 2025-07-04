<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Models\Event;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    $events = Event::orderBy('startDate', 'desc')->get();
    return view('dashboard', compact('events'));
});

Route::get('/events', function () {
    return view('events');
});

Route::get('/friends', function () {
    return view('friends');
})->middleware('auth');

Route::get('/signup', function () {
    return view('auth.signup');
});

Route::get('/signin', function () {
    return view('auth.signin');
})->name('login');

// User routes
Route::post('/loginPost', [AuthController::class, 'login'])->name('loginPost');
Route::post('/user/create', [UserController::class, 'createUser']);
Route::post('/admin/create', [UserController::class, 'createAdmin']);
Route::get('/user/{id}', [UserController::class, 'show']);

// Event Routes
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events/create', [EventController::class, 'create'])->middleware('auth')->name('events.create');
Route::put('/events/update/{id}', [EventController::class, 'update']);
Route::delete('/events/delete/{id}', [EventController::class, 'delete']);

// Event Participant Routes
Route::get('/participants', [EventParticipantController::class, 'index']);
Route::get('/participant/{id}', [EventParticipantController::class, 'show']);
Route::delete('/participant/delete/{id}', [EventParticipantController::class, 'delete']);