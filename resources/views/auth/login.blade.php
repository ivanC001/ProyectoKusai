<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion | Kusay.pe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #173e2f;
            --muted: #638372;
            --line: #d7e2db;
            --paper: #ffffff;
            --brand-900: #103928;
            --brand-700: #1b6c49;
            --brand-500: #3fb173;
            --danger: #b34242;
            --ok: #14613f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: "Manrope", sans-serif;
            background:
                radial-gradient(circle at 12% 10%, rgba(95, 188, 133, .20), transparent 28%),
                radial-gradient(circle at 88% 88%, rgba(79, 163, 113, .18), transparent 34%),
                linear-gradient(155deg, #0b2418 0%, #16533a 52%, #2f8358 100%);
            display: grid;
            place-items: center;
            padding: 26px 14px;
        }

        .auth-wrap {
            width: min(1100px, 100%);
            display: grid;
            grid-template-columns: 1.08fr .92fr;
            background: rgba(255, 255, 255, .96);
            border: 1px solid rgba(23, 62, 47, .14);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 24px 44px rgba(9, 34, 24, .30);
        }

        .auth-cover {
            padding: 42px 34px;
            color: #ecfff5;
            background:
                linear-gradient(180deg, rgba(8, 27, 20, .45), rgba(8, 27, 20, .65)),
                linear-gradient(150deg, #0f3b2a, #1d7a52);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 30px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: "Fraunces", serif;
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -.4px;
            text-decoration: none;
            color: inherit;
        }

        .cover-copy h1 {
            margin: 0 0 8px;
            font-family: "Fraunces", serif;
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            line-height: 1.08;
        }

        .cover-copy p {
            margin: 0;
            color: rgba(236, 255, 245, .86);
            line-height: 1.5;
        }

        .cover-points {
            display: grid;
            gap: 10px;
            margin-top: 6px;
        }

        .cover-points span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(236, 255, 245, .9);
            font-size: .94rem;
            font-weight: 600;
        }

        .auth-card {
            background: #f9fcfa;
            padding: 34px;
            display: grid;
            align-content: center;
        }

        .title {
            margin: 0 0 4px;
            font-family: "Fraunces", serif;
            font-size: 1.9rem;
            line-height: 1.15;
            color: #124735;
        }

        .subtitle {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: .95rem;
        }

        .alert {
            border-radius: 11px;
            padding: 11px 12px;
            font-size: .9rem;
            margin-bottom: 13px;
        }

        .alert-ok {
            border: 1px solid #9dcdb6;
            background: #edf8f2;
            color: var(--ok);
        }

        .alert-error {
            border: 1px solid #efc1c1;
            background: #fff1f1;
            color: var(--danger);
        }

        .alert-error ul {
            margin: 0;
            padding-left: 18px;
        }

        .field {
            margin-bottom: 12px;
        }

        .label {
            display: inline-block;
            margin-bottom: 6px;
            font-size: .84rem;
            font-weight: 800;
            color: #2f5a49;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 11px;
            padding: 11px 12px;
            font: inherit;
            color: var(--ink);
            background: #fff;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .input:focus {
            border-color: #5aa77e;
            box-shadow: 0 0 0 3px rgba(86, 166, 122, .18);
        }

        .check-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin: 6px 0 2px;
            flex-wrap: wrap;
        }

        .check {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #4e7161;
            font-size: .9rem;
        }

        .link {
            color: #1a6e49;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 700;
        }

        .link:hover { text-decoration: underline; }

        .btn {
            width: 100%;
            border: none;
            border-radius: 11px;
            background: linear-gradient(130deg, var(--brand-700), var(--brand-900));
            color: #fff;
            font: inherit;
            font-weight: 800;
            padding: 12px;
            cursor: pointer;
            margin-top: 14px;
            box-shadow: 0 10px 22px rgba(16, 57, 40, .22);
        }

        .btn:hover { filter: brightness(1.05); }

        .bottom {
            margin-top: 13px;
            color: var(--muted);
            font-size: .9rem;
            text-align: center;
        }

        @media (max-width: 940px) {
            .auth-wrap {
                grid-template-columns: 1fr;
            }

            .auth-cover {
                padding: 26px 24px;
            }

            .auth-card {
                padding: 24px 20px;
            }
        }
    </style>
</head>
<body>
    <main class="auth-wrap">
        <section class="auth-cover">
            <a href="{{ url('/') }}" class="brand">Kusay.pe</a>

            <div class="cover-copy">
                <h1>Accede a tu panel y publica propiedades</h1>
                <p>Gestiona tus anuncios, fotos y contactos desde una sola cuenta.</p>
                <div class="cover-points">
                    <span>Publica propiedades gratis</span>
                    <span>Recibe mensajes directos</span>
                    <span>Administra todo desde tu panel</span>
                </div>
            </div>

            <a href="{{ route('register') }}" class="link" style="color:#d6f3e3; font-weight:800;">No tienes cuenta? Crea tu cuenta</a>
        </section>

        <section class="auth-card">
            <h1 class="title">Iniciar sesion</h1>
            <p class="subtitle">Ingresa con tu correo y contrasena.</p>

            @if (session('status'))
                <div class="alert alert-ok">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label class="label" for="email">Correo</label>
                    <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <div class="field">
                    <label class="label" for="password">Contrasena</label>
                    <input class="input" id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <div class="check-row">
                    <label class="check" for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="link" href="{{ route('password.request') }}">Olvidaste tu contrasena?</a>
                    @endif
                </div>

                <button class="btn" type="submit">Entrar</button>
            </form>

            <p class="bottom">
                Quieres publicar una propiedad?
                <a class="link" href="{{ route('register') }}">Publica gratis</a>
            </p>
        </section>
    </main>
</body>
</html>
