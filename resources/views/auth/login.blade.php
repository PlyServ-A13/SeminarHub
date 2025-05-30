<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeminarHub - Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #8b5cf6;
            --accent: #0ea5e9;
            --light: #f8fafc;
            --dark: #0f172a;
            --gray: #64748b;
            --light-gray: #f1f5f9;
            --gradient: linear-gradient(135deg, var(--primary), var(--secondary));
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 20px;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--primary);
            top: 10%;
            left: 5%;
            filter: blur(40px);
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            background: var(--secondary);
            bottom: 15%;
            right: 10%;
            filter: blur(40px);
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
            z-index: 10;
        }

        .auth-header {
            background: var(--gradient);
            color: white;
            padding: 28px 32px;
            text-align: center;
        }

        .auth-logo {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .auth-logo i {
            font-size: 2.4rem;
        }

        .auth-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 5px;
        }

        .auth-body {
            padding: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .input-field {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: var(--transition);
            background: var(--light-gray);
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: var(--primary);
        }

        .remember-me label {
            margin-bottom: 0;
            color: var(--gray);
            font-size: 0.95rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            border: none;
        }

        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
        }

        .auth-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .university-badge {
            text-align: center;
            margin-top: 30px;
            font-size: 0.95rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .errors-container {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .status-message {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            color: #2f855a;
            font-weight: 500;
        }

        @media (max-width: 600px) {
            .auth-header {
                padding: 20px;
            }
            
            .auth-body {
                padding: 24px;
            }
            
            .auth-footer {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <x-guest-layout>
        <x-authentication-card>
            <x-slot name="logo">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-graduation-cap"></i>
                        SeminarHub
                    </div>
                    <div class="auth-subtitle">Plateforme Académique d'Événements Scientifiques</div>
                </div>
            </x-slot>

            <div class="auth-body">
                <x-validation-errors class="errors-container" />

                @session('status')
                    <div class="status-message">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <input id="email" class="input-field" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="votre@email.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>

                    <div class="remember-me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <label for="remember_me">Se souvenir de moi</label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </div>

                    <div class="auth-footer">
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                <i class="fas fa-key"></i> Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                </form>

                <div class="university-badge">
                    <i class="fas fa-university"></i>
                    Institut des Mathématiques et Sciences Physiques (IMSP)
                </div>
            </div>
        </x-authentication-card>
    </x-guest-layout>

    <script>
        // Animation pour les formes flottantes
        document.addEventListener('DOMContentLoaded', function() {
            const shapes = document.querySelectorAll('.shape');
            
            function animateShapes() {
                shapes.forEach((shape, index) => {
                    const speed = index === 0 ? 0.2 : 0.3;
                    const time = Date.now() * speed * 0.001;
                    const x = Math.sin(time) * 20;
                    const y = Math.cos(time) * 15;
                    
                    shape.style.transform = `translate(${x}px, ${y}px)`;
                });
                
                requestAnimationFrame(animateShapes);
            }
            
            animateShapes();
            
            // Animation du bouton au survol
            const button = document.querySelector('.btn-primary');
            if (button) {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }
        });
    </script>
</body>
</html>