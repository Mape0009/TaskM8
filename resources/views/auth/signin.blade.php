<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/theme-toggle.js') }}"></script>
    <style>
        html {
            font-size: 75%; 
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--color-background-primary);
            color: var(--color-text-primary);
            padding: var(--spacing-unit) * 4;
        }
        .auth-container {
            background-color: var(--color-background-secondary);
            padding: calc(var(--spacing-unit) * 6);
            border-radius: 16px;
            box-shadow: 0 8px 30px var(--color-shadow-dark);
            width: 100%;
            max-width: 450px;
            text-align: center;
            border: 1px solid var(--color-border);
        }
        .auth-container h2 {
            font-size: calc(var(--spacing-unit) * 4);
            color: var(--color-text-primary);
            margin-bottom: calc(var(--spacing-unit) * 4);
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .input-group {
            margin-bottom: calc(var(--spacing-unit) * 3);
            text-align: left;
        }
        .input-group label {
            display: block;
            font-size: calc(var(--spacing-unit) * 1.75);
            color: var(--color-text-secondary);
            margin-bottom: calc(var(--spacing-unit) * 1);
            font-weight: 500;
        }
        .input-group input {
            width: calc(100% - (var(--spacing-unit) * 4)); /* Adjust for padding */
            padding: calc(var(--spacing-unit) * 2);
            background-color: var(--color-background-tertiary);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            color: var(--color-text-primary);
            font-size: calc(var(--spacing-unit) * 1.875);
            outline: none;
            box-shadow: inset 0 1px 4px var(--color-shadow-light);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .input-group input:focus {
            border-color: var(--color-accent-primary);
            box-shadow: inset 0 1px 4px var(--color-shadow-dark), 0 0 0 3px rgba(0, 123, 255, 0.2);
        }
        .auth-container .btn {
            width: 100%;
            margin-top: calc(var(--spacing-unit) * 4);
            padding: calc(var(--spacing-unit) * 2);
            font-size: calc(var(--spacing-unit) * 2);
            font-weight: 700;
            border-radius: 10px;
            background: linear-gradient(145deg, var(--color-accent-primary), #0056b3);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease-out;
            box-shadow: 0 4px 12px var(--color-shadow-dark);
        }
        .auth-container .btn:hover {
            background: linear-gradient(145deg, #0056b3, var(--color-accent-primary));
            box-shadow: 0 6px 16px var(--color-shadow-dark);
        }
        .auth-container p {
            margin-top: calc(var(--spacing-unit) * 4);
            font-size: calc(var(--spacing-unit) * 1.75);
            color: var(--color-text-secondary);
        }
        .auth-container p a {
            color: var(--color-accent-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .auth-container p a:hover {
            color: var(--color-text-accent);
        }

        /* Responsive Adjustments for Auth Pages */
        @media (max-width: 576px) {
            .auth-container {
                padding: calc(var(--spacing-unit) * 4);
            }
            .auth-container h2 {
                font-size: calc(var(--spacing-unit) * 3.5);
                margin-bottom: calc(var(--spacing-unit) * 3);
            }
            .input-group label {
                font-size: calc(var(--spacing-unit) * 1.625);
            }
            .input-group input {
                padding: calc(var(--spacing-unit) * 1.5);
                font-size: calc(var(--spacing-unit) * 1.75);
            }
            .auth-container .btn {
                padding: calc(var(--spacing-unit) * 1.5);
                font-size: calc(var(--spacing-unit) * 1.875);
            }
            .auth-container p {
                font-size: calc(var(--spacing-unit) * 1.625);
            }
        }
        /* Theme toggle button styles for auth pages */
        .auth-theme-toggle {
            position: fixed;
            top: calc(var(--spacing-unit) * 3);
            right: calc(var(--spacing-unit) * 3);
            background: var(--color-background-secondary);
            border: 1px solid var(--color-border);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px var(--color-shadow-dark);
            z-index: 1000;
        }
        
        .auth-theme-toggle:hover {
            background: var(--color-background-tertiary);
            transform: scale(1.05);
        }
        
        .auth-theme-toggle .icon {
            width: 20px;
            height: 20px;
            color: var(--color-text-primary);
            transition: color 0.2s ease;
        }
        
        .auth-theme-toggle .moon { display: none; }
        .auth-theme-toggle.dark .sun { display: none; }
        .auth-theme-toggle.dark .moon { display: inline; }
    </style>
</head>
<body>
    <button class="auth-theme-toggle" id="theme-toggle-btn" aria-label="Toggle dark/light mode">
        <svg class="icon sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="icon moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
    </button>
    <div class="auth-container">
        <h2>Login</h2>
        <form action="{{ route('loginPost') }}" method="POST">
            @csrf
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
</html> 