<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion | Kusay.pe</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/png kusay.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/image/png kusay.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        /* Ruta de estilos base: resources/views/auth/login.blade.php */
        body {
            min-height: 100vh;
            font-family: "Manrope", sans-serif;
            background:
                radial-gradient(circle at 14% 8%, rgba(110, 198, 145, .30), transparent 30%),
                radial-gradient(circle at 82% 84%, rgba(88, 177, 128, .22), transparent 30%),
                linear-gradient(160deg, #0b2418 0%, #145338 52%, #2f8358 100%);
        }
        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.2rem;
        }
        .auth-card {
            width: min(1080px, 100%);
            border: 0;
            border-radius: 1.1rem;
            overflow: hidden;
            box-shadow: 0 1.2rem 2.4rem rgba(10, 37, 26, .36);
        }
        .auth-cover {
            background:
                linear-gradient(180deg, rgba(11, 38, 27, .66), rgba(11, 38, 27, .78)),
                linear-gradient(155deg, #0f3b2a, #1f7b53);
            color: #f2fff8;
            height: 100%;
            padding: 2rem 1.6rem;
        }
        .auth-cover h1,
        .auth-brand {
            font-family: "Fraunces", serif;
        }
        .auth-brand {
            color: #173e2f;
            text-decoration: none;
            position: relative;
            background: linear-gradient(145deg, #ffffff, #f2faf5);
            border: 1px solid rgba(18, 71, 53, .2);
            border-radius: 1rem;
            padding: .65rem .95rem;
            box-shadow:
                0 .9rem 1.7rem rgba(16, 57, 40, .18),
                inset 0 1px 0 rgba(255, 255, 255, .85);
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }
        .auth-brand:hover {
            transform: translateY(-2px) scale(1.01);
            border-color: rgba(18, 71, 53, .35);
            box-shadow:
                0 1.1rem 2rem rgba(16, 57, 40, .24),
                inset 0 1px 0 rgba(255, 255, 255, .92);
        }
        .auth-brand::after {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, .5);
            pointer-events: none;
        }
        .auth-logo-icon {
            width: 38px;
            height: auto;
            display: block;
            filter: drop-shadow(0 3px 5px rgba(16, 57, 40, .25));
        }
        .auth-logo-text {
            width: 188px;
            max-width: 48vw;
            height: auto;
            display: block;
            filter: drop-shadow(0 1px 2px rgba(16, 57, 40, .2));
        }
        .auth-form-wrap {
            background: #f7fcf9;
            padding: 2rem 1.6rem;
        }
        .required-mark {
            color: #b53737;
            font-weight: 800;
            margin-left: .2rem;
        }
        .social-btn {
            border-radius: .7rem;
            font-weight: 700;
        }
        .login-btn {
            border-radius: .7rem;
            font-weight: 800;
            background: linear-gradient(130deg, #1b6c49, #103928);
            border: none;
        }
        .legend-chip {
            border-radius: 999px;
            border: 1px solid #cdded3;
            background: #ffffff;
            color: #3a6654;
            font-size: .8rem;
            font-weight: 700;
            padding: .35rem .7rem;
        }
        .text-muted-soft {
            color: #628372;
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <div class="card auth-card">
            <div class="row g-0">
                <!-- Menu lateral de marca -->
                <section class="col-lg-5">
                    <div class="auth-cover d-flex flex-column justify-content-between">
                        <div>
                            <a href="{{ url('/') }}" class="auth-brand d-inline-flex align-items-center gap-2">
                                <img src="{{ asset('assets/image/png kusay.png') }}" alt="Kusay" class="auth-logo-icon">
                                <img src="{{ asset('assets/image/png 2.png') }}" alt="Kusay.pe" class="auth-logo-text">
                            </a>
                        </div>
                        <div>
                            <h1 class="display-6 fw-bold mb-3">Inicia sesion y gestiona tus publicaciones</h1>
                            <p class="mb-3 text-white-50">Controla tus propiedades, solicitudes y visitas desde un solo panel.</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i><span>Publica propiedades gratis</span></li>
                                <li class="mb-2 d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i><span>Responde solicitudes rapidamente</span></li>
                                <li class="d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i><span>Monitorea favoritos y clics</span></li>
                            </ul>
                        </div>
                        <p class="mb-0 text-white-50">No tienes cuenta? <a href="{{ route('register') }}" class="link-light fw-semibold">Registrate aqui</a></p>
                    </div>
                </section>

                <!-- Body del formulario -->
                <section class="col-lg-7">
                    <div class="auth-form-wrap">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <div>
                                <h2 class="h3 fw-bold mb-1">Iniciar sesion</h2>
                                <p class="text-muted-soft mb-0">Ingresa con tu correo y contrasena.</p>
                            </div>
                            <!-- <span class="legend-chip">Campos con <span class="required-mark">*</span> son obligatorios</span> -->
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success py-2">{{ session('status') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger py-2">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold mb-1">Correo electronico <span class="required-mark">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control form-control-lg">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold mb-1">Contrasena <span class="required-mark">*</span></label>
                                <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control form-control-lg">
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember_me">Recordarme</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="link-success fw-semibold">Olvidaste tu contrasena?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 login-btn">Entrar</button>
                        </form>

                        <div class="position-relative text-center my-3">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle px-3 bg-light text-muted small">o continua con</span>
                        </div>

                        <div class="row g-2">
                            <div class="col-sm-6">
                                <a href="{{ route('auth.social.redirect', ['provider' => 'google']) }}" class="btn btn-outline-secondary w-100 social-btn">
                                    <i class="bi bi-google me-2"></i>Google
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('auth.social.redirect', ['provider' => 'facebook']) }}" class="btn btn-outline-primary w-100 social-btn">
                                    <i class="bi bi-facebook me-2"></i>Facebook
                                </a>
                            </div>
                        </div>

                        <p class="text-center mt-4 mb-0 text-muted-soft">
                            Quieres publicar una propiedad ?
                            <a href="{{ route('register') }}" class="fw-semibold link-success"> Crea tu cuenta</a>
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
