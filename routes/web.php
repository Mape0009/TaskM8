<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/events', function () {
    return view('events');
});

Route::get('/friends', function () {
    return view('friends');
});

Route::get('/signup', function () {
    return view('auth.signup');
});

Route::get('/signin', function () {
    return view('auth.signin');
});

Route::get('/events/{id}', function ($id, Request $request) {
    // Dummy data for two events
    $dummyEvents = [
        1 => [
            'title' => 'Dummy Data Title',
            'start_time' => '2025-07-01 10:00',
            'end_time' => '2025-07-01 11:00',
            'location' => 'Mercantec',
            'description' => 'Beskrivelse med dummy data.',
        ],
        2 => [
            'title' => 'Havnefest',
            'start_time' => '2025-07-05 18:00',
            'end_time' => '2025-07-05 23:00',
            'location' => 'Havnen',
            'description' => 'Husk bajer',
        ],
    ];
    $event = $dummyEvents[$id] ?? null;
    if (!$event) {
        abort(404);
    }
    return view('event', ['event' => (object)$event]);
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