<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contrasena | Kusay.pe</title>
        <!-- Icono que se muestra en la pestaña del navegador -->
    <link rel="icon" type="image/png" href="{{ asset('assets/image/png kusay.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/image/png kusay.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #173e2f;
            --line: #d7e2db;
            --danger: #b34242;
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
            width: min(520px, 100%);
            background: #fff;
            border: 1px solid #dbe7df;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 20px 34px rgba(9, 35, 24, .28);
        }
        h1 {
            margin: 0 0 10px;
            font-family: "Fraunces", serif;
            color: #124735;
            font-size: 1.85rem;
        }
        .label { display: block; font-size: .82rem; font-weight: 800; margin: 0 0 6px; text-transform: uppercase; color: #2f5a49; }
        .input {
            width: 100%; border: 1px solid var(--line); border-radius: 10px; padding: 11px 12px; font: inherit;
            margin-bottom: 10px;
        }
        .btn {
            margin-top: 4px; width: 100%; border: none; border-radius: 10px; padding: 11px;
            background: linear-gradient(130deg, var(--brand-700), var(--brand-900)); color: #fff; font: inherit; font-weight: 800; cursor: pointer;
        }
        .alert { border: 1px solid #efc1c1; background: #fff1f1; color: var(--danger); border-radius: 10px; padding: 10px 11px; margin-bottom: 10px; font-size: .9rem; }
        .alert ul { margin: 0; padding-left: 18px; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Define tu nueva contrasena</h1>

        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <label class="label" for="email">Correo</label>
            <input class="input" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">

            <label class="label" for="password">Contrasena</label>
            <input class="input" id="password" type="password" name="password" required autocomplete="new-password">

            <label class="label" for="password_confirmation">Confirmar contrasena</label>
            <input class="input" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

            <button class="btn" type="submit">Actualizar contrasena</button>
        </form>
    </main>
</body>
</html>
