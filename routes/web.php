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

Route::view('/events/{id}/edit', 'events.edit')->middleware('auth')->name('events.edit');


Route::post('/events/{eventId}/invite', [MailController::class, 'sendEventInvites'])->name('events.invite');
Route::view('test', 'test');
Route::post('test', [MailController::class, 'sendEventInvites'])->name('events.invite');

Route::get('/events', function () {
    return view('events');
});

Route::get('/friends', function () {
    return view('friends');
})->middleware('auth');


Route::view('signup', 'auth.signup');
Route::view('signin', 'auth.signin')->name('login');

// User routes
Route::post('/loginPost', [AuthController::class, 'login'])->name('loginPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/user/create', [UserController::class, 'createUser'])->name('user.create');
Route::post('/admin/create', [UserController::class, 'createAdmin']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('user.change-password')->middleware('auth');

// Event Routes
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events/create', [EventController::class, 'create'])->middleware('auth')->name('events.create');
Route::put('/events/update/{id}', [EventController::class, 'update']) ->middleware('auth')->name('events.update');
Route::delete('/events/delete/{id}', [EventController::class, 'delete']);

Route::get('/events/{id}/edit', [EventController::class, 'edit'])
    ->middleware('auth')
    ->name('events.edit');

// Event Participant Routes
Route::get('/participants', [EventParticipantController::class, 'index']);
Route::post('/events/clear-success', [EventController::class, 'clearSuccessMessage']);
Route::get('/participant/{id}', [EventParticipantController::class, 'show']);
Route::delete('/participant/delete/{id}', [EventParticipantController::class, 'delete']);
