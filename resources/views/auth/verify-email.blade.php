<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar correo | Kusay.pe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #173e2f;
            --muted: #638372;
            --line: #d7e2db;
            --ok: #14613f;
            --brand-900: #103928;
            --brand-700: #1b6c49;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background: linear-gradient(155deg, #0b2418 0%, #16533a 52%, #2f8358 100%);
            display: grid;
            place-items: center;
            padding: 16px;
        }
        .card {
            width: min(620px, 100%);
            background: #fff;
            border: 1px solid #dbe7df;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 20px 34px rgba(9, 35, 24, .28);
        }
        h1 {
            margin: 0 0 8px;
            font-family: "Fraunces", serif;
            color: #124735;
            font-size: 1.95rem;
        }
        p {
            margin: 0 0 14px;
            color: var(--muted);
            line-height: 1.5;
        }
        .alert {
            border: 1px solid #9dcdb6;
            background: #edf8f2;
            color: var(--ok);
            border-radius: 10px;
            padding: 10px 11px;
            margin-bottom: 12px;
            font-size: .9rem;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }
        .btn-main {
            background: linear-gradient(130deg, var(--brand-700), var(--brand-900));
            color: #fff;
        }
        .btn-outline {
            border: 1px solid #bfd2c7;
            background: #fff;
            color: #355f4d;
        }
        .home {
            display: inline-block;
            margin-top: 10px;
            color: #1a6e49;
            font-weight: 700;
            text-decoration: none;
        }
        .home:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Verifica tu correo</h1>
        <p>Antes de continuar, confirma tu correo desde el enlace que enviamos. Si no te llego, puedes reenviarlo aqui.</p>

        @if (session('status') === 'verification-link-sent')
            <div class="alert">Te enviamos un nuevo enlace de verificacion a tu correo.</div>
        @endif

        <div class="actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-main">Reenviar verificacion</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline">Cerrar sesion</button>
            </form>
        </div>

        <a class="home" href="{{ url('/') }}">Volver al inicio</a>
    </main>
</body>
</html>
