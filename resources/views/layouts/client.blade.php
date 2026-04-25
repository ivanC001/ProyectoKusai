<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kusay.pe')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --client-bg: #f2f7f4;
            --client-paper: #ffffff;
            --client-line: #d2dfd7;
            --client-ink: #183f30;
            --client-soft: #5f7f71;
            --client-brand: #17573c;
            --client-brand-2: #2d8f5f;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--client-ink);
            background: var(--client-bg);
        }
        a { color: inherit; text-decoration: none; }

        .client-nav {
            position: sticky;
            top: 0;
            z-index: 40;
            min-height: 72px;
            border-bottom: 1px solid var(--client-line);
            background: rgba(250, 253, 251, .95);
            backdrop-filter: blur(12px);
        }
        .client-nav-wrap {
            width: min(1240px, 94vw);
            margin: 0 auto;
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .brand {
            font-family: "Fraunces", serif;
            font-size: 1.62rem;
            font-weight: 800;
            letter-spacing: -.4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .brand .dot { color: #dd7f45; }
        .brand .pe { color: var(--client-brand-2); }
        .brand-icon { font-size: 1.02em; }

        .client-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .user-menu {
            position: relative;
        }
        .user-summary {
            list-style: none;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
        }
        .user-summary::-webkit-details-marker {
            display: none;
        }
        .user-caret {
            font-size: .8rem;
            opacity: .8;
        }
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #e3eee7;
            color: #24503e;
            font-size: .82rem;
            font-weight: 800;
            overflow: hidden;
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-fullname {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            min-width: 190px;
            border-radius: 12px;
            border: 1px solid #d0ddd6;
            background: #fff;
            box-shadow: 0 12px 26px rgba(16, 57, 40, .16);
            overflow: hidden;
            z-index: 45;
        }
        .user-item {
            display: block;
            width: 100%;
            border: none;
            border-bottom: 1px solid #edf2ef;
            background: #fff;
            color: #2f5948;
            font: inherit;
            font-size: .9rem;
            font-weight: 700;
            text-align: left;
            padding: 11px 12px;
            cursor: pointer;
            text-decoration: none;
        }
        .user-item:last-child {
            border-bottom: none;
        }
        .user-item:hover {
            background: #f2f8f4;
        }
        .logout-inline {
            margin: 0;
        }
        .btn {
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: .92rem;
            border: 1px solid transparent;
        }
        .btn-outline {
            border-color: #b7cbbf;
            background: #fff;
            color: #335e4c;
        }
        .btn-solid {
            background: linear-gradient(130deg, var(--client-brand-2), var(--client-brand));
            color: #fff;
        }

        main.client-main {
            padding: 24px 0 34px;
        }

        .client-footer {
            border-top: 1px solid rgba(255, 255, 255, .1);
            background: #112b1f;
            color: #d8e7de;
        }
        .client-footer-wrap {
            width: min(1240px, 94vw);
            margin: 0 auto;
            padding: 20px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }
        .client-footer small {
            color: #9bb4a7;
            font-size: .84rem;
        }

        @media (max-width: 720px) {
            .client-actions { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <header class="client-nav">
        <div class="client-nav-wrap">
            <a href="/" class="brand"><span class="brand-icon">🌿</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <div class="client-actions">
                @auth
                    <a href="{{ route('propiedades.mine') }}" class="btn btn-outline">Mis publicaciones</a>
                    <details class="user-menu">
                        <summary class="btn btn-outline user-summary">
                            <span class="user-avatar">
                                @if (auth()->user()->tieneFotoPerfil())
                                    <img src="{{ route('profile.photo') }}" alt="Foto">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </span>
                            <span class="user-fullname">{{ trim(auth()->user()->name.' '.auth()->user()->apellidos) }}</span>
                            <span class="user-caret">▼</span>
                        </summary>
                        <div class="user-dropdown">
                            <a href="{{ route('propiedades.mine') }}" class="user-item">Mis publicaciones</a>
                            <a href="{{ route('profile.edit') }}" class="user-item">Ver y editar perfil</a>
                            <form method="POST" action="{{ route('logout') }}" class="logout-inline">
                                @csrf
                                <button type="submit" class="user-item">Cerrar sesion</button>
                            </form>
                        </div>
                    </details>
                    <a href="{{ route('propiedades.create') }}" class="btn btn-solid">Publica gratis</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Iniciar sesion</a>
                    <a href="{{ route('register') }}" class="btn btn-solid">Publica gratis</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="client-main">
        @yield('content')
    </main>

    <footer class="client-footer">
        <div class="client-footer-wrap">
            <a href="/" class="brand"><span class="brand-icon">🌿</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <small>Portal inmobiliario de la selva y sierra peruana.</small>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
