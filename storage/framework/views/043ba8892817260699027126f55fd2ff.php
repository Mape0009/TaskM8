<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friends</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/modal.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'friends'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content-full">
        <header class="content-header">
            <h1></h1>
        </header>
        

        <section class="friends-listing">
            <h2>Alle Medlemmer</h2>
            <div class="friend-list">
                <!-- Friend Card 1 -->
                <div class="friend-card event-card"> 
                    <div class="friend-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 24px;">UT</div>
                        <div>
                            <h3>Bruger Test</h3>
                            <p style="color: #8b949e; font-size: 14px; margin-top: 5px;">Bruger.test@gmail.com</p>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn primary-btn">Inviter</button>
                    </div>
                </div>

                <!-- Friend Card 2 -->
                <div class="friend-card event-card">
                    <div class="friend-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 24px; background-color: #e67e22;">TU</div>
                        <div>
                            <h3>Test Bruger</h3>
                            <p style="color: #8b949e; font-size: 14px; margin-top: 5px;">Test.bruger@mail.com</p>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn primary-btn">Inviter</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html> 
<?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/friends.blade.php ENDPATH**/ ?>