<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friends</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'friends'])

    <main class="main-content-full">
        <header class="content-header">
            <h1>My Friends</h1>
        </header>
        <div class="search-bar">
            <input type="text" placeholder="Search your friends...">
        </div>

        <section class="friends-listing">
            <h2>All My Friends</h2>
            <div class="friend-list">
                <!-- Friend Card 1 -->
                <div class="friend-card event-card"> {{-- Reusing event-card styling --}}
                    <div class="friend-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 24px;">UT</div>
                        <div>
                            <h3>User Test</h3>
                            <p style="color: #8b949e; font-size: 14px; margin-top: 5px;">User.test@example.com</p>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn primary-btn">View Profile</button>
                    </div>
                </div>

                <!-- Friend Card 2 -->
                <div class="friend-card event-card">
                    <div class="friend-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 24px; background-color: #e67e22;">TU</div>
                        <div>
                            <h3>Test User</h3>
                            <p style="color: #8b949e; font-size: 14px; margin-top: 5px;">test.user@example.com</p>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn primary-btn">View Profile</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html> 
