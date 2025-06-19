<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
            'title' => 'Monthly Team Sync',
            'start_time' => '2025-07-01 10:00',
            'end_time' => '2025-07-01 11:00',
            'location' => 'Main Office',
            'description' => 'A recurring meeting to align on project progress and upcoming tasks.',
        ],
        2 => [
            'title' => 'Grill Night',
            'start_time' => '2025-07-05 18:00',
            'end_time' => '2025-07-05 23:00',
            'location' => 'Backyard',
            'description' => 'Beers with the boys',
        ],
    ];
    $event = $dummyEvents[$id] ?? null;
    if (!$event) {
        abort(404);
    }
    return view('event', ['event' => (object)$event]);
});
