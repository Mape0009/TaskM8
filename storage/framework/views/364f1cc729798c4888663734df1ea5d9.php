<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TaskM8</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="<?php echo e(asset('js/theme-toggle.js')); ?>"></script>
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>
        <form action="<?php echo e(route('loginPost')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Skriv email" required>
            </div>
            <div class="input-group">
                <label for="password">Adgangskode</label>
                <input type="password" id="password" name="password" placeholder="Skriv adgangskode" required>
            </div>
            <button type="submit" class="btn primary-btn">Login</button>
        </form>
        <p>Har du ingen konto? <a href="/signup">Opret Konto</a></p>
    </div>
</body>
</html> <?php /**PATH C:\Users\Tobia\Documents\GitHub\TaskM8\resources\views/auth/signin.blade.php ENDPATH**/ ?>