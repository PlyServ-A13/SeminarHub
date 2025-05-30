<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeminarHub - Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
            --success: #10b981;
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
            padding: 20px;
            position: relative;
            overflow: auto; /* Correction du défilement */
        }

        .floating-shapes {
            position: fixed; /* Changé à fixed pour rester en place pendant le défilement */
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

        .shape-3 {
            width: 150px;
            height: 150px;
            background: var(--success);
            top: 50%;
            left: 80%;
            filter: blur(30px);
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
            z-index: 10;
            margin: 40px 0; /* Marge pour le défilement */
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
            position: relative;
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

        .select-field {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            background: var(--light-gray);
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em 1.5em;
        }

        .select-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            margin-top: 20px;
        }

        .checkbox-container input {
            margin-top: 5px;
            margin-right: 10px;
            accent-color: var(--primary);
        }

        .checkbox-container label {
            margin-bottom: 0;
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .checkbox-container a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .checkbox-container a:hover {
            text-decoration: underline;
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
            margin-top: 24px;
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
            margin-top: 20px;
        }

        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-link:hover {
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

        .password-strength {
            margin-top: 8px;
            height: 6px;
            border-radius: 3px;
            background-color: #e2e8f0;
            overflow: hidden;
        }

        .password-strength-meter {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            background-color: var(--success);
        }

        .password-hint {
            font-size: 0.85rem;
            margin-top: 4px;
            color: var(--gray);
        }

        .role-icon {
            position: absolute;
            right: 16px;
            top: 40px;
            color: var(--primary);
            font-size: 1.2rem;
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
            
            body {
                padding: 15px;
                align-items: flex-start; /* Permet le défilement sur mobile */
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <x-guest-layout>
        <x-authentication-card>
            <x-slot name="logo">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-graduation-cap"></i>
                        SeminarHub
                    </div>
                    <div class="auth-subtitle">Rejoignez notre communauté académique</div>
                </div>
            </x-slot>

            <div class="auth-body">
                <x-validation-errors class="errors-container" />

                <form method="POST" action="{{ route('register') }}" x-data="{ selectedRole: '{{ old('role', 'étudiant') }}' }">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nom</label>
                        <div class="relative">
                            <input id="name" class="input-field" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Votre nom">
                            <i class="fas fa-user absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <div class="relative">
                            <input id="prenom" class="input-field" type="text" name="prenom" :value="old('prenom')" required autocomplete="given-name" placeholder="Votre prénom">
                            <i class="fas fa-signature absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <div class="relative">
                            <input id="email" class="input-field" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="votre@email.com">
                            <i class="fas fa-envelope absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Je suis un(e)</label>
                        <div class="relative">
                            <select name="role" id="role" class="select-field" x-model="selectedRole">
                                <option value="étudiant">Étudiant(e)</option>
                                <option value="présentateur">Présentateur(trice)</option>
                                <option value="secretaire">Secrétaire</option>
                            </select>
                            <div class="role-icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"
                         x-show="selectedRole === 'présentateur' || selectedRole === 'secretaire'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-90"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-90">
                        <label for="registration_code">Code d'inscription</label>
                        <div class="relative">
                            <input id="registration_code" class="input-field" type="text" name="registration_code" :value="old('registration_code')" autocomplete="off" placeholder="Code secret">
                            <i class="fas fa-key absolute right-3 top-4 text-gray-400"></i>
                        </div>
                        <p class="password-hint">Code requis pour les présentateurs et secrétaires</p>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <div class="relative">
                            <input id="password" class="input-field" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" x-on:keyup="updatePasswordStrength($event)">
                            <i class="fas fa-lock absolute right-3 top-4 text-gray-400"></i>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-meter" x-ref="strengthMeter"></div>
                        </div>
                        <p class="password-hint">Minimum 8 caractères avec chiffres et lettres</p>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <div class="relative">
                            <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                            <i class="fas fa-lock absolute right-3 top-4 text-gray-400"></i>
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="checkbox-container">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms">
                            {!! __('J\'accepte les :terms_of_service et la :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline">Conditions d\'Utilisation</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline">Politique de Confidentialité</a>',
                            ]) !!}
                        </label>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Créer mon compte
                    </button>

                    <div class="auth-footer">
                        <a class="login-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Déjà un compte ? Se connecter
                        </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Animation pour les formes flottantes
            const shapes = document.querySelectorAll('.shape');
            
            function animateShapes() {
                shapes.forEach((shape, index) => {
                    const speed = index === 0 ? 0.2 : (index === 1 ? 0.3 : 0.25);
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
            
            // Fonction pour mesurer la force du mot de passe
            function updatePasswordStrength(event) {
                const password = event.target.value;
                const meter = document.querySelector('.password-strength-meter');
                let strength = 0;
                
                // Longueur minimale
                if (password.length >= 8) strength += 25;
                
                // Contient des lettres
                if (/[a-zA-Z]/.test(password)) strength += 25;
                
                // Contient des chiffres
                if (/[0-9]/.test(password)) strength += 25;
                
                // Contient des caractères spéciaux
                if (/[^a-zA-Z0-9]/.test(password)) strength += 25;
                
                meter.style.width = strength + '%';
                
                // Mettre à jour la couleur en fonction de la force
                if (strength < 50) {
                    meter.style.backgroundColor = '#ef4444'; // rouge
                } else if (strength < 75) {
                    meter.style.backgroundColor = '#f59e0b'; // orange
                } else {
                    meter.style.backgroundColor = '#10b981'; // vert
                }
            }
            
            // Initialiser la force du mot de passe si le champ est pré-rempli
            const passwordField = document.getElementById('password');
            if (passwordField && passwordField.value) {
                const event = new Event('keyup');
                passwordField.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>