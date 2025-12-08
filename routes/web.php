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
use App\Models\Mail as MailModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;


Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        $userId = auth()->id();
        // Use EventController helper to fetch events the same way as events.index
        $controller = app(EventController::class);
        $events = $controller->getEventsForUser($userId)->sortByDesc('startDate')->values();
        $participant = EventParticipant::where('userId', $userId)->get();

        $participatedEventsCount = $events->count();
        $pendingEventsCount = EventParticipant::where('userId', $userId)->where('status', 'pending')->count();
        $previousInviteesCount = MailModel::where('senderId', $userId)
            ->where('recipientId', '!=', $userId)
            ->distinct('recipientId')
            ->count('recipientId');
        $totalUsers = null;
        $totalEvents = null;
    } else {
        $events = collect();
        $participatedEventsCount = 0;
        $pendingEventsCount = 0;
        $previousInviteesCount = 0;
        $participant = collect();
        $totalUsers = User::count();
        $totalEvents = Event::count();
    }

    return view('dashboard', compact('events', 'participatedEventsCount', 'pendingEventsCount', 'previousInviteesCount', 'totalUsers', 'totalEvents', 'participant'));
});


Route::view('/events/{id}/edit', 'events.edit')->middleware('auth')->name('events.edit');   


Route::post('/events/{eventId}/invite', [MailController::class, 'sendEventInvites'])->name('events.invite');
Route::get('/events/{eventId}/invitees', [MailController::class, 'getPreviousInvitees'])->middleware('auth')->name('events.invitees');
Route::view('test', 'test');
Route::post('test', [MailController::class, 'sendEventInvites'])->name('events.invite');

Route::get('/events', [EventController::class, 'index'])->name('events.index');

// previousEvents route 
Route::get('/previousEvents', function () {
    if (!auth()->check()) { return redirect('/signin'); }
    $userId = auth()->id();
    $controller = app(EventController::class);
    $previousEvents = $controller->getPreviousEventsForUser($userId)->sortByDesc('endDate')->values();
    $participant = App\Models\EventParticipant::where('userId', $userId)->get();
    return view('previousEvents', compact('previousEvents', 'participant'));
})->middleware('auth');

// Legal policy pages
Route::view('/privatlivspolitik', 'legal.privatlivspolitik');
Route::view('/cookiepolitik', 'legal.cookiepolitik');
Route::view('/vilkar', 'legal.vilkar');


Route::get('signup', function(Request $request){
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
Route::get('/events/{id}.json', [EventController::class, 'json'])->middleware('auth');
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events/create', [EventController::class, 'create'])->middleware('auth')->name('events.create');
Route::put('/events/update/{id}', [EventController::class, 'update']) ->middleware('auth')->name('events.update');
Route::delete('/events/delete/{id}', [EventController::class, 'delete'])->middleware('auth');

Route::get('/events/{id}/edit', [EventController::class, 'edit'])
    ->middleware('auth')
    ->name('events.edit');

// Event Participant Routes
Route::get('/organizerOverview/{eventId}', [EventParticipantController::class, 'index'])->middleware('auth')->name('events.participants');
Route::post('/events/clear-success', [EventController::class, 'clearSuccessMessage']);
Route::get('/participant/{id}', [EventParticipantController::class, 'show']);
Route::delete('/participant/delete/{id}', [EventParticipantController::class, 'delete'])->name('events.deleteParticipant');
Route::post('/events/{eventId}/join', [EventParticipantController::class, 'join'])->middleware('auth')->name('events.join');
Route::post('/events/{eventId}/decline', [EventParticipantController::class, 'decline'])->middleware('auth')->name('events.decline');
Route::post('/events/{eventId}/rsvp', [EventParticipantController::class, 'rsvp'])->middleware('auth')->name('events.rsvp');
Route::get('/events/{eventId}/participants-list', [EventParticipantController::class, 'getParticipantsList'])->middleware('auth')->name('events.participants-list');
Route::post('/organizerOverview/roleUpdate', [EventParticipantController::class, 'roleUpdate'])->middleware('auth')->name('events.roleUpdate');


// task Routes
Route::view('task', 'task');
Route::get('/tasks/create', [TaskController::class, 'showCreateForm'])->name('task.create.form');
Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('task.details');
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks/create', [TaskController::class, 'create'])->name('task.create');
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::delete('/tasks/{id}', [TaskController::class, 'delete'])->name('task.delete');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('task.update');

// Event-scoped task routes
Route::get('/events/{eventId}/tasks', [TaskController::class, 'indexByEvent'])->name('events.tasks.index');
Route::get('/events/{eventId}/tasks/create', [TaskController::class, 'showCreateFormForEvent'])->name('events.tasks.create.form');
Route::post('/events/{eventId}/tasks', [TaskController::class, 'createForEvent'])->name('events.tasks.create');

// Shift routes
Route::get('/tasks/{taskId}/shifts', [ShiftController::class, 'index'])->name('tasks.shifts.index');
Route::get('/tasks/{taskId}/shifts/create', [ShiftController::class, 'create'])->name('tasks.shifts.create');
Route::post('/tasks/{taskId}/shifts', [ShiftController::class, 'store'])->name('tasks.shifts.store');
Route::get('/tasks/{taskId}/shifts/{shiftId}/edit', [ShiftController::class, 'edit'])->name('tasks.shifts.edit');
Route::put('/tasks/{taskId}/shifts/{shiftId}', [ShiftController::class, 'update'])->name('tasks.shifts.update');
Route::delete('/tasks/{taskId}/shifts/{shiftId}', [ShiftController::class, 'destroy'])->name('tasks.shifts.destroy');
Route::post('/tasks/{taskId}/join', [ShiftController::class, 'join'])->name('tasks.join');
Route::post('/tasks/{taskId}/leave', [ShiftController::class, 'leave'])->name('tasks.leave');

//Sitemap route
Route::get('/generate-sitemap', [SitemapController::class, 'generateSitemap']);
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path)) {
        return abort(404);
    }
    return response()->file($path, [
        'Content-Type' => 'application/xml'
    ]);
});
Route::post('/tasks/create', [TaskController::class, 'create'])->name('task.create');
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::delete('/tasks/{id}', [TaskController::class, 'delete'])->name('task.delete');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('task.update');

// Event-scoped task routes
Route::get('/events/{eventId}/tasks', [TaskController::class, 'indexByEvent'])->name('events.tasks.index');
Route::get('/events/{eventId}/tasks/create', [TaskController::class, 'showCreateFormForEvent'])->name('events.tasks.create.form');
Route::post('/events/{eventId}/tasks', [TaskController::class, 'createForEvent'])->name('events.tasks.create');

// Shift routes
Route::get('/tasks/{taskId}/shifts', [ShiftController::class, 'index'])->name('tasks.shifts.index');
Route::get('/tasks/{taskId}/shifts/create', [ShiftController::class, 'create'])->name('tasks.shifts.create');
Route::post('/tasks/{taskId}/shifts', [ShiftController::class, 'store'])->name('tasks.shifts.store');
Route::get('/tasks/{taskId}/shifts/{shiftId}/edit', [ShiftController::class, 'edit'])->name('tasks.shifts.edit');
Route::put('/tasks/{taskId}/shifts/{shiftId}', [ShiftController::class, 'update'])->name('tasks.shifts.update');
Route::delete('/tasks/{taskId}/shifts/{shiftId}', [ShiftController::class, 'destroy'])->name('tasks.shifts.destroy');
Route::post('/tasks/{taskId}/join', [ShiftController::class, 'join'])->name('tasks.join');
Route::post('/tasks/{taskId}/leave', [ShiftController::class, 'leave'])->name('tasks.leave');

// Group routes
Route::view('groups/create', 'group.groupCreation');
Route::view('groups/overview', 'group.groupOverview');
Route::post('groups/create', [GroupController::class, 'create'])->name('groups.create');
Route::get('groups/overview', [GroupController::class, 'index'])->name('groups.overview');
Route::delete('groups/delete/{id}', [GroupController::class, 'delete'])->name('groups.delete');


// Group Member routes
Route::view('groups/members/{groupId}', 'group.groupMembers');
Route::get('groups/members/{groupId}', [GroupMemberController::class, 'index'])->name('groupMember.index');
Route::get('groups/{id}/invite', [GroupMemberController::class, 'showUsers'])->name('groupMember.invite');
Route::post('groups/{groupId}/invite', [GroupMemberController::class, 'invite'])->name('groupMember.invite.post');
Route::delete('groupMember/delete/{id}', [GroupMemberController::class, 'delete'])->name('groupMember.delete');

//Sitemap route
Route::get('/generate-sitemap', [SitemapController::class, 'generateSitemap']);
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path)) {
        return abort(404);
    }
    return response()->file($path, [
        'Content-Type' => 'application/xml'
    ]);
});