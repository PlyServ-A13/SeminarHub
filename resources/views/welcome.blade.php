<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SeminarHub - Plateforme Académique d'Événements Scientifiques</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #8b5cf6;
            --accent: #0ea5e9;
            --light: #f0f9ff;
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
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            z-index: 10;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            font-size: 2.5rem;
            color: white;
        }

        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, white, #e0f2fe);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .university-badge {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            padding: 0.5rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 500;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            position: relative;
        }

        .hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            gap: 4rem;
        }

        .hero-text {
            flex: 1;
            max-width: 600px;
        }

        .hero-image {
            flex: 1;
            max-width: 500px;
            display: flex;
            justify-content: center;
        }

        .hero h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            max-width: 550px;
        }

        .highlight {
            background: linear-gradient(120deg, rgba(37, 99, 235, 0.15), rgba(139, 92, 246, 0.15));
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .auth-buttons {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            gap: 0.6rem;
            border: none;
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: var(--gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: rgba(37, 99, 235, 0.05);
            transform: translateY(-3px);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
            width: 100%;
            max-width: 1200px;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .feature-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-description {
            color: var(--gray);
            font-size: 1rem;
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

        footer {
            background: var(--dark);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .copyright {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .university-name {
            font-weight: 600;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .social-links a {
            color: white;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .social-links a:hover {
            color: var(--accent);
            transform: translateY(-3px);
        }

        @media (max-width: 900px) {
            .hero-content {
                flex-direction: column;
                gap: 3rem;
                text-align: center;
            }
            
            .hero-text {
                max-width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
            
            .auth-buttons {
                justify-content: center;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }

        @media (max-width: 600px) {
            .auth-buttons {
                flex-direction: column;
                gap: 1rem;
            }
            
            .hero h1 {
                font-size: 2.3rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <i class="fas fa-graduation-cap logo-icon"></i>
                <div class="logo-text">SeminarHub</div>
            </div>
            <div class="university-badge">
                <i class="fas fa-university"></i>
                Institut de Mathématiques et Sciences Physiques (IMSP)
            </div>
        </div>
    </header>

    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <main class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Plateforme Académique d'Événements Scientifiques</h1>
                <p>
                    <span class="highlight">SeminarHub</span> est la plateforme de référence pour découvrir, organiser et participer aux séminaires scientifiques à l'<span class="highlight">IMSP</span>. 
                    Connectez-vous à la communauté scientifique et restez informé des dernières avancées.
                </p>
                
                <div class="auth-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}">
                            Dashboard
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Créer un compte
                            </a>
                        @endif    
                        <a href="{{ route('login') }}" class="btn btn-outline">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                    @endauth    
                </div>
            </div>
            
            <div class="hero-image">
                <svg width="450" height="450" viewBox="0 0 600 600">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#2563eb" />
                            <stop offset="100%" stop-color="#8b5cf6" />
                        </linearGradient>
                    </defs>
                    <circle cx="300" cy="300" r="250" fill="url(#gradient)" opacity="0.1" />
                    <path d="M200,250 Q300,150 400,250 T600,250" stroke="url(#gradient)" stroke-width="15" fill="none" />
                    <circle cx="250" cy="250" r="25" fill="#2563eb" />
                    <circle cx="350" cy="250" r="25" fill="#8b5cf6" />
                    <circle cx="450" cy="250" r="25" fill="#0ea5e9" />
                    <path d="M150,400 L450,400 M150,450 L450,450 M150,500 L450,500" stroke="#2563eb" stroke-width="8" />
                    <path d="M250,350 L350,350" stroke="#8b5cf6" stroke-width="8" />
                    <path d="M200,350 L200,450" stroke="#0ea5e9" stroke-width="8" />
                    <path d="M400,350 L400,450" stroke="#2563eb" stroke-width="8" />
                </svg>
            </div>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="feature-title">Calendrier Intégré</h3>
                <p class="feature-description">Planifiez et suivez tous les événements scientifiques dans un calendrier unifié et interactif.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">Réseau Académique</h3>
                <p class="feature-description">Connectez-vous avec des chercheurs, professeurs et étudiants de l'IMSP et au-delà.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3 class="feature-title">Annonces en Temps Réel</h3>
                <p class="feature-description">Recevez des notifications instantanées pour les nouveaux séminaires et événements.</p>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="university-name">
                <i class="fas fa-university"></i>
                Institut des Mathématiques et Sciences Physiques (IMSP)
            </div>
            <div class="social-links">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <p class="copyright">© 2025 SeminarHub - Tous droits réservés. Plateforme développée par l'étudiant ADEHAN Aéman Ibrahim Ulrich de l'IMSP</p>
        </div>
    </footer>

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
            
            // Animation des boutons au survol
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>