<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friends</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/header.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials.header', ['currentPage' => 'friends'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="main-content-full">
        
        <section class="friends-listing">
            <h2>Grupper</h2>
            <div class="friend-list">
                <!-- Friend Card 1 -->
                <div class="friend-card event-card"> 
                    <div class="friend-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 24px;">HF</div>
                        <div>
                            <h3>Havnefest - Gruppe</h3>
                            <p style="color: #8b949e; font-size: 14px; margin-top: 5px;">Se medlemmer knap (Mangler at blive lavet)</p>
                        </div>
                    </div>
                    
                    <div class="event-actions"></div>
                </div>

                <!-- Friend Card 2 -->
                <div class="friend-card event-card">
                    <div class="friend-header" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="avatar" style="width: 60px; height: 60px; font-size: 24px; background-color: #e67e22;">KK</div>
                        <div>
                            <h3>Kano Klubben</h3>
                            <p style="color: #8b949e; font-size: 14px; margin-top: 5px;">Se medlemmer knap (Mangler at blive lavet)</p>
                        </div>
                    </div>
                    
                    <div class="event-actions"></div>
                </div>
            </div>
        </section>
    </main>
</body>
</html> 
<?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/friends.blade.php ENDPATH**/ ?>