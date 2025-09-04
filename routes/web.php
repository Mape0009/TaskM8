<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        $userId = auth()->id();
        $ownedEvents = Event::where('ownerId', $userId);
        $participantEventIds = EventParticipant::where('userId', $userId)->pluck('eventId');
        $participatedEvents = Event::whereIn('id', $participantEventIds);
        $events = $ownedEvents->union($participatedEvents)->orderBy('startDate', 'desc')->get();

        $participatedEventsCount = EventParticipant::where('userId', $userId)->count();
        $pendingEventsCount = EventParticipant::where('userId', $userId)->where('status', 'pending')->count();
    } else {
        $events = collect();
        $participatedEventsCount = 0;
        $pendingEventsCount = 0;
    }
    return view('dashboard', compact('events', 'participatedEventsCount', 'pendingEventsCount'));
});

Route::view('organizerOverview', 'organizerOverview');

Route::view('/events/{id}/edit', 'events.edit')->middleware('auth')->name('events.edit');


Route::post('/events/{eventId}/invite', [MailController::class, 'sendEventInvites'])->name('events.invite');
Route::get('/events/{eventId}/invitees', [MailController::class, 'getPreviousInvitees'])->middleware('auth')->name('events.invitees');

Route::get('/events', [EventController::class, 'index']);

Route::get('/friends', function () {
    return view('friends');
})->middleware('auth');


Route::get('signup', function(Request $request){
    // If secure token present, decode into request inputs for prefill
    if ($request->filled('token')) {
        $raw = base64_decode($request->query('token'));
        $data = json_decode($raw, true);
        if (is_array($data)) {
            $request->merge([
                'email' => $data['email'] ?? $request->query('email'),
                'pin' => $data['pin'] ?? $request->query('pin'),
                'event' => $data['event'] ?? $request->query('event'),
            ]);
        }
    }
    return view('auth.signup');
});
Route::view('signin', 'auth.signin')->name('login');

// User routes
Route::post('/loginPost', [AuthController::class, 'login'])->name('loginPost');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/user/create', [UserController::class, 'createUser'])->name('user.create');
Route::post('/admin/create', [UserController::class, 'createAdmin']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('user.change-password')->middleware('auth');

// Event Routes
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
Route::post('/events/{eventId}/join', [EventParticipantController::class, 'join'])->middleware('auth')->name('events.join');
Route::post('/events/{eventId}/decline', [EventParticipantController::class, 'decline'])->middleware('auth')->name('events.decline');
Route::post('/events/{eventId}/rsvp', [EventParticipantController::class, 'rsvp'])->middleware('auth')->name('events.rsvp');
Route::get('organizerOverview/{eventId}', [EventParticipantController::class, 'index'])->middleware('auth')->name('events.participants');