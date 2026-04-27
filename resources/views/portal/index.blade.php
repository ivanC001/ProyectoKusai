<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kusay.pe | Portal inmobiliario</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-900: #103928;
            --green-800: #15553a;
            --green-700: #1b6c49;
            --green-500: #3fb173;
            --bg-soft: #f3f7f4;
            --line: #d9e3dc;
            --text: #173e2f;
            --text-soft: #5f7f71;
            --radius: 14px;
            --shadow: 0 16px 36px rgba(12, 45, 31, 0.16);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Manrope", sans-serif;
            background: #f9fbfa;
            color: var(--text);
            line-height: 1.5;
        }
        a { color: inherit; text-decoration: none; }
        img { width: 100%; display: block; }
        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-main {
            background: linear-gradient(130deg, var(--green-700), var(--green-900));
            color: #fff;
            box-shadow: 0 10px 24px rgba(16, 57, 40, .26);
        }
        .btn-outline {
            background: #fff;
            color: var(--green-700);
            border: 1.5px solid #8eb59f;
        }
        .btn-wa {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #23cf63;
            color: #fff;
            border: 1px solid #23cf63;
            box-shadow: 0 8px 18px rgba(22, 114, 54, .24);
        }
        .btn-wa-icon {
            font-size: .95rem;
            line-height: 1;
        }
        #nav {
            position: sticky;
            top: 0;
            z-index: 40;
            height: 76px;
            padding: 0 40px;
            border-bottom: 1px solid var(--line);
            background: rgba(250, 253, 251, .95);
            backdrop-filter: blur(14px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .logo {
            font-family: "Fraunces", serif;
            font-size: 1.66rem;
            font-weight: 800;
            letter-spacing: -.4px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .logo-icon {
            font-size: 1.1em;
            line-height: 1;
            filter: drop-shadow(0 1px 0 rgba(8, 39, 26, .18));
        }
        .logo .dot { color: #df8347; }
        .logo .pe { color: var(--green-700); }
        .nav-links {
            display: flex;
            list-style: none;
            gap: 22px;
        }
        .nav-links a {
            color: var(--text-soft);
            font-size: .94rem;
            font-weight: 700;
            padding: 8px 1px;
        }
        .nav-links a.active { color: var(--green-900); }
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mobile-nav {
            display: none;
            position: relative;
        }
        .mobile-nav-summary {
            list-style: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid #bfd0c6;
            background: #fff;
            color: #25523f;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(16, 57, 40, .14);
        }
        .mobile-nav-summary::-webkit-details-marker {
            display: none;
        }
        .mobile-nav-glyph {
            font-size: 1.35rem;
            line-height: 1;
            transform: translateY(-1px);
        }
        .mobile-nav[open] .mobile-nav-summary {
            background: #123f2d;
            color: #fff;
            border-color: #123f2d;
        }
        .mobile-nav-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            min-width: 220px;
            border-radius: 12px;
            border: 1px solid #d0ddd6;
            background: #fff;
            box-shadow: 0 12px 26px rgba(16, 57, 40, .16);
            overflow: hidden;
            z-index: 50;
        }
        .mobile-nav-link {
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
        .mobile-nav-link:last-child {
            border-bottom: none;
        }
        .mobile-nav-link:hover {
            background: #f2f8f4;
        }
        .mobile-nav-form {
            margin: 0;
        }
        .mobile-user-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-bottom: 1px solid #edf2ef;
            background: #f7fbf8;
        }
        .mobile-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #e3eee7;
            color: #24503e;
            font-size: .86rem;
            font-weight: 800;
            overflow: hidden;
            flex-shrink: 0;
        }
        .mobile-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .mobile-user-copy {
            min-width: 0;
        }
        .mobile-user-name {
            margin: 0;
            color: #234c3b;
            font-size: .87rem;
            font-weight: 800;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .mobile-user-email {
            margin: 2px 0 0;
            color: #5f7f71;
            font-size: .8rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
        }
        .user-item:last-child {
            border-bottom: none;
        }
        .logout-inline {
            margin: 0;
        }
        .quick-nav {
            position: sticky;
            top: 76px;
            z-index: 35;
            border-bottom: 1px solid var(--line);
            box-shadow: 0 8px 16px rgba(14, 45, 32, .08);
        }
        .quick-categories-wrap {
            background: #f6faf8;
            border-bottom: 1px solid var(--line);
        }
        .quick-categories {
            padding: 10px 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .cat-toggle-row {
            display: none;
            justify-content: center;
            padding: 0 12px 10px;
        }
        .cat-toggle-btn {
            border: 1px solid #bfd0c6;
            background: #fff;
            color: #25523f;
            border-radius: 999px;
            font: inherit;
            font-size: .86rem;
            font-weight: 700;
            padding: 6px 12px;
            cursor: pointer;
        }
        .cat-btn {
            border: 1px solid transparent;
            background: transparent;
            color: #6a897b;
            font-size: 1.02rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 8px 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .cat-icon {
            font-size: .98rem;
            line-height: 1;
        }
        .cat-btn.active {
            color: var(--green-900);
            border-color: #9cbcae;
            background: #eef6f1;
        }
        .quick-filters {
            background: #eaefeb;
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .chip-row {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
        }
        .chip {
            border: 1px solid #cdd9d2;
            background: #f9fbfa;
            color: #385f4e;
            border-radius: 999px;
            padding: 8px 15px;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
        }
        .chip.active {
            background: var(--green-900);
            color: #fff;
            border-color: var(--green-900);
        }
        .quick-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ord-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ord-select {
            border: 1px solid #cfdad3;
            border-radius: 10px;
            padding: 9px 12px;
            min-width: 200px;
            background: #fff;
            color: #315947;
            font: inherit;
        }
        .count {
            color: #6a8678;
            font-weight: 700;
        }
        .hero {
            min-height: 74vh;
            padding: 88px 20px 54px;
            text-align: center;
            color: #fff;
            background:
                radial-gradient(circle at 12% 8%, rgba(95, 188, 133, .24) 0%, transparent 30%),
                radial-gradient(circle at 85% 90%, rgba(79, 163, 113, .2) 0%, transparent 35%),
                linear-gradient(155deg, #0b2418 0%, #16533a 52%, #2f8358 100%);
        }
        .hero-inner { max-width: 900px; margin: 0 auto; }
        .hero-tag {
            display: inline-flex;
            border-radius: 999px;
            border: 1px solid rgba(145, 235, 185, .44);
            background: rgba(90, 187, 131, .2);
            color: #bcefd2;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 700;
            padding: 7px 14px;
            margin-bottom: 18px;
        }
        .hero h1 {
            font-family: "Fraunces", serif;
            font-size: clamp(2.2rem, 5.4vw, 4rem);
            line-height: 1.08;
            margin-bottom: 12px;
        }
        .hero h1 em { color: #63d09b; font-style: italic; }
        .hero-sub {
            max-width: 690px;
            margin: 0 auto 24px;
            color: rgba(255, 255, 255, .82);
        }
        .search-box {
            max-width: 880px;
            margin: 0 auto;
            border-radius: var(--radius);
            overflow: hidden;
            background: #fff;
            box-shadow: var(--shadow);
            color: var(--text);
        }
        .search-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid var(--line);
        }
        .search-tabs a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #729082;
            font-weight: 700;
            padding: 14px 8px;
            border-bottom: 3px solid transparent;
            background: #fff;
        }
        .search-tabs a.active {
            color: var(--green-900);
            border-bottom-color: var(--green-900);
            background: rgba(21, 85, 58, .05);
        }
        .search-body { padding: 16px; }
        .search-form .row {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 8px;
            margin-bottom: 8px;
        }
        .search-form .row2 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        input, select {
            border: 1.5px solid #d6e0da;
            border-radius: 10px;
            padding: 11px 12px;
            font: inherit;
            color: var(--text);
            background: #f5faf7;
            width: 100%;
        }
        .search-btn {
            border: none;
            border-radius: 10px;
            background: var(--green-900);
            color: #fff;
            font-weight: 700;
            padding: 11px 18px;
            cursor: pointer;
        }
        .sec { padding: 64px 40px; }
        .shead {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .eyebrow {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--green-700);
            font-weight: 800;
            margin-bottom: 6px;
        }
        .stitle {
            font-family: "Fraunces", serif;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            line-height: 1.15;
        }
        .ssub { color: var(--text-soft); max-width: 640px; }
        .vip-sec { background: linear-gradient(180deg, #fffbed, #fff8e0); }
        .vip-grid, .prop-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .card {
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(13, 35, 25, .08);
        }
        .card-cover {
            width: 100%;
            height: 210px;
            object-fit: cover;
            background: #dde9e2;
        }
        .card-empty {
            width: 100%;
            height: 210px;
            display: grid;
            place-items: center;
            color: #5b7f6e;
            background: linear-gradient(140deg, #dfece5, #eff6f2);
            font-weight: 700;
        }
        .card-body { padding: 12px 14px 14px; }
        .card-type { color: var(--green-700); font-weight: 700; text-transform: uppercase; font-size: .68rem; }
        .card-title { font-family: "Fraunces", serif; font-size: 1rem; margin: 4px 0; }
        .card-loc { color: #6a8678; font-size: .82rem; margin-bottom: 10px; }
        .card-price { color: #b04e27; font-family: "Fraunces", serif; font-size: 1.2rem; font-weight: 800; }
        .meta-row {
            margin-top: 8px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .meta-chip {
            border-radius: 999px;
            border: 1px solid #ccdad2;
            background: #f4f9f6;
            color: #3f6757;
            font-size: .72rem;
            font-weight: 700;
            padding: 4px 8px;
        }
        .card-link {
            margin-top: 10px;
            display: inline-flex;
            color: #1b6c49;
            font-weight: 800;
            font-size: .88rem;
        }
        .empty-list {
            border: 1px dashed #bad0c4;
            border-radius: 16px;
            background: #f5faf7;
            padding: 26px;
            text-align: center;
            color: #5f7f71;
        }
        .pager {
            margin-top: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .pager a,
        .pager span {
            min-width: 40px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #ccd9d1;
            background: #fff;
            color: #2f5948;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            text-decoration: none;
            font-weight: 800;
            font-size: .88rem;
        }
        .pager span.current {
            color: #fff;
            background: #17573c;
            border-color: #17573c;
        }
        .ciu-sec { background: #0f2319; color: #fff; }
        .ciu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .city-card {
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .03);
            padding: 16px;
        }
        .city-card h3 {
            font-family: "Fraunces", serif;
            font-size: 1.2rem;
            margin-bottom: 4px;
        }
        .city-card p {
            color: rgba(255, 255, 255, .82);
            font-size: .88rem;
        }
        .guia-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        .step {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px;
        }
        .step h3 { font-family: "Fraunces", serif; margin: 8px 0; font-size: 1.02rem; }
        .step p { color: var(--text-soft); font-size: .9rem; }
        footer {
            background: #102a1d;
            color: #d7e6dc;
            padding: 36px 40px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }
        .footer-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .foot-note { color: #9fb8aa; font-size: .86rem; }
        @media (max-width: 1100px) {
            .nav-links { display: none; }
            .quick-filters { padding: 10px 16px; }
            .vip-grid, .prop-grid { grid-template-columns: repeat(2, 1fr); }
            .guia-grid, .ciu-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 760px) {
            #nav { padding: 0 14px; }
            .nav-actions { display: none; }
            .mobile-nav { display: block; }
            .quick-categories {
                justify-content: flex-start;
                overflow-x: auto;
                overflow-y: hidden;
                flex-wrap: nowrap;
                scrollbar-width: none;
            }
            .quick-categories::-webkit-scrollbar {
                display: none;
            }
            .quick-categories-wrap.has-hidden .cat-toggle-row {
                display: flex;
            }
            .quick-categories-wrap.is-expanded .quick-categories {
                flex-wrap: wrap;
                overflow: visible;
                padding-bottom: 6px;
            }
            .quick-right { width: 100%; margin-left: 0; justify-content: flex-start; }
            .sec { padding: 44px 16px; }
            .search-form .row, .search-form .row2 { grid-template-columns: 1fr; }
            .vip-grid, .prop-grid, .guia-grid, .ciu-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @php
        $tipoProyecto = $tiposPropiedad->first(function ($tipo) {
            return str_contains(mb_strtolower($tipo->nombre), 'proyecto');
        });

        $resolveTipoIcono = static function (string $nombre): string {
            $normalizado = Illuminate\Support\Str::ascii(mb_strtolower(trim($nombre)));

            return match (true) {
                str_contains($normalizado, 'terreno') => '&#127793;',
                str_contains($normalizado, 'chacra') => '&#127806;',
                str_contains($normalizado, 'casa') => '&#127968;',
                str_contains($normalizado, 'departamento') => '&#127970;',
                str_contains($normalizado, 'local') => '&#127980;',
                str_contains($normalizado, 'lote') => '&#128207;',
                str_contains($normalizado, 'oficina') => '&#128188;',
                str_contains($normalizado, 'proyecto') => '&#127959;&#65039;',
                default => '&#127968;',
            };
        };

        $whatsappUrl = 'https://www.whatsapp.com/';
        $queryBase = request()->except(['page']);
    @endphp

    <header id="nav">
        <a href="{{ route('home') }}" class="logo"><span class="logo-icon">&#127807;</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
        <ul class="nav-links">
            <li><a class="active" href="#props">Propiedades</a></li>
            <li><a href="#ciudades">Ciudades</a></li>
            <li><a href="#publicar">Como publicar</a></li>
            <li><a href="#publicar">Planes</a></li>
            <li><a href="#publicar">Financiamiento</a></li>
            <li><a href="#destacadas">Destacadas</a></li>
        </ul>
        <div class="nav-actions">
            @auth
                <a href="{{ route('propiedades.mine') }}" class="btn btn-outline">Mis publicaciones</a>
                <details class="user-menu">
                    <summary class="btn btn-outline user-summary">
                        <span class="user-avatar">
                            @if (auth()->user()->tieneFotoPerfil())
                                <img src="{{ route('profile.photo', ['v' => optional(auth()->user()->updated_at)->timestamp]) }}" alt="Foto">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </span>
                        <span class="user-fullname">{{ trim(auth()->user()->name.' '.auth()->user()->apellidos) }}</span>
                        <span class="user-caret">&#9662;</span>
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
               
                <a href="{{ route('propiedades.create') }}" class="btn btn-main">Publica gratis</a>
            @else
               
                <a href="{{ route('login') }}" class="btn btn-outline">Iniciar sesion</a>
                <a href="{{ route('register') }}" class="btn btn-main">Publica gratis</a>
            @endauth
        </div>
        <details class="mobile-nav">
            <summary class="mobile-nav-summary" aria-label="Abrir menu">
                <span class="mobile-nav-glyph">&#9776;</span>
            </summary>
            <div class="mobile-nav-dropdown">
                @auth
                    <div class="mobile-user-head">
                        <span class="mobile-user-avatar">
                            @if (auth()->user()->tieneFotoPerfil())
                                <img src="{{ route('profile.photo', ['v' => optional(auth()->user()->updated_at)->timestamp]) }}" alt="Foto">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </span>
                        <div class="mobile-user-copy">
                            <p class="mobile-user-name">{{ trim(auth()->user()->name.' '.auth()->user()->apellidos) }}</p>
                            <p class="mobile-user-email">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('propiedades.mine') }}" class="mobile-nav-link">Mis publicaciones</a>
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-link">Ver y editar perfil</a>
                    <a href="{{ route('propiedades.create') }}" class="mobile-nav-link">Publica gratis</a>
                    <form method="POST" action="{{ route('logout') }}" class="mobile-nav-form">
                        @csrf
                        <button type="submit" class="mobile-nav-link">Cerrar sesion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-link">Iniciar sesion</a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">Crear cuenta</a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">Publica gratis</a>
                @endauth
            </div>
        </details>
    </header>

    <section class="quick-nav">
        <div class="quick-categories-wrap" data-cat-wrap>
            <div class="quick-categories" data-cat-list id="quick-categories-list">
                <a
                    href="{{ route('home', array_merge($queryBase, ['tipo_propiedad_id' => null])) }}#props"
                    class="cat-btn {{ $filtros['tipo_propiedad_id'] === null ? 'active' : '' }}"
                >
                    <span class="cat-icon">&#127968;</span>Todos
                </a>
                @foreach ($tiposPropiedad as $tipo)
                    @php
                        $iconoTipo = $resolveTipoIcono($tipo->nombre);
                    @endphp
                    <a
                        href="{{ route('home', array_merge($queryBase, ['tipo_propiedad_id' => $tipo->id])) }}#props"
                        class="cat-btn {{ (string) $filtros['tipo_propiedad_id'] === (string) $tipo->id ? 'active' : '' }}"
                    >
                        <span class="cat-icon">{!! $iconoTipo !!}</span>{{ $tipo->nombre }}
                    </a>
                @endforeach
            </div>
            <div class="cat-toggle-row">
                <button
                    type="button"
                    class="cat-toggle-btn"
                    data-cat-toggle
                    aria-expanded="false"
                    aria-controls="quick-categories-list"
                >
                    Ver mas categorias
                </button>
            </div>
        </div>

        <div class="quick-filters">
            <div class="chip-row">
                <a
                    class="chip {{ $filtros['ciudad'] === '' ? 'active' : '' }}"
                    href="{{ route('home', array_merge($queryBase, ['ciudad' => null])) }}#props"
                >
                    Todo el Peru
                </a>
                @foreach ($ciudadesTop as $ciudad)
                    <a
                        class="chip {{ $filtros['ciudad'] === $ciudad->distrito ? 'active' : '' }}"
                        href="{{ route('home', array_merge($queryBase, ['ciudad' => $ciudad->distrito])) }}#props"
                    >
                        {{ ucwords(mb_strtolower($ciudad->distrito)) }}
                    </a>
                @endforeach
            </div>

            <div class="quick-right">
                <form class="ord-form" method="GET" action="{{ route('home') }}#props">
                    @foreach (request()->except(['orden', 'page']) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select class="ord-select" name="orden" onchange="this.form.submit()" aria-label="Ordenar propiedades">
                        <option value="recientes" @selected($filtros['orden'] === 'recientes')>Mas recientes</option>
                        <option value="precio_asc" @selected($filtros['orden'] === 'precio_asc')>Menor precio</option>
                        <option value="precio_desc" @selected($filtros['orden'] === 'precio_desc')>Mayor precio</option>
                    </select>
                </form>
                <span class="count">{{ $totalResultados }} propiedades</span>
            </div>
        </div>
    </section>

    <main>
        <section class="hero" id="inicio">
            <div class="hero-inner">
                <div class="hero-tag">Portal inmobiliario N1 - Selva y Sierra peruana</div>
                <h1>Tu proxima propiedad en la <em>Selva</em> y <em>Sierra</em> del Peru</h1>
                <p class="hero-sub">Terrenos, casas, departamentos, lotes, chacras y mas. Publica gratis y conecta directo con compradores.</p>

                <div class="search-box">
                    <div class="search-tabs">
                        <a
                            href="{{ route('home', array_merge($queryBase, ['operacion' => 'venta'])) }}#props"
                            class="{{ $filtros['operacion'] === 'venta' ? 'active' : '' }}"
                        >
                            Comprar
                        </a>
                        <a
                            href="{{ route('home', array_merge($queryBase, ['operacion' => 'alquiler'])) }}#props"
                            class="{{ $filtros['operacion'] === 'alquiler' ? 'active' : '' }}"
                        >
                            Alquilar
                        </a>
                        <a
                            href="{{ route('home', array_merge($queryBase, ['operacion' => null, 'tipo_propiedad_id' => $tipoProyecto?->id])) }}#props"
                            class="{{ $tipoProyecto && (string) $filtros['tipo_propiedad_id'] === (string) $tipoProyecto->id ? 'active' : '' }}"
                        >
                            Proyectos
                        </a>
                    </div>
                    <div class="search-body">
                        <form class="search-form" method="GET" action="{{ route('home') }}#props">
                            <input type="hidden" name="operacion" value="{{ $filtros['operacion'] }}">
                            <input type="hidden" name="ciudad" value="{{ $filtros['ciudad'] }}">
                            <input type="hidden" name="orden" value="{{ $filtros['orden'] }}">

                            <div class="row">
                                <input
                                    type="text"
                                    name="ubicacion"
                                    value="{{ $filtros['ubicacion'] }}"
                                    placeholder="Ciudad, zona o referencia..."
                                >
                                <select name="tipo_propiedad_id">
                                    <option value="">Todo tipo</option>
                                    @foreach ($tiposPropiedad as $tipo)
                                        <option value="{{ $tipo->id }}" @selected((string) $filtros['tipo_propiedad_id'] === (string) $tipo->id)>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="search-btn" type="submit">Buscar</button>
                            </div>

                            <div class="row2">
                                <input type="number" step="0.01" min="0" name="precio_min" value="{{ $filtros['precio_min'] }}" placeholder="Precio min. (S/.)">
                                <input type="number" step="0.01" min="0" name="precio_max" value="{{ $filtros['precio_max'] }}" placeholder="Precio max. (S/.)">
                                <input type="number" step="0.01" min="0" name="area_min" value="{{ $filtros['area_min'] }}" placeholder="Area min. (m2)">
                                <select name="dormitorios">
                                    <option value="">Dormitorios</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" @selected((string) $filtros['dormitorios'] === (string) $i)>{{ $i }}+</option>
                                    @endfor
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="vip-sec sec" id="destacadas">
            <div class="shead">
                <div>
                    <p class="eyebrow">Seccion VIP</p>
                    <h2 class="stitle">Propiedades destacadas</h2>
                    <p class="ssub">Mayor visibilidad y contacto directo.</p>
                </div>
            </div>
            <div class="vip-grid">
                @forelse ($destacadas as $propiedad)
                    <article class="card">
                        @if ($propiedad->portadaImagen)
                            <img class="card-cover" src="{{ route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen]) }}" alt="Portada de {{ $propiedad->titulo }}">
                        @else
                            <div class="card-empty">Sin foto</div>
                        @endif
                        <div class="card-body">
                            <p class="card-type">{{ $propiedad->tipoPropiedad?->nombre ?? 'Propiedad' }}</p>
                            <h3 class="card-title">{{ $propiedad->titulo }}</h3>
                            <p class="card-loc">
                                {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                                {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                            </p>
                            <p class="card-price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                            <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">Ver propiedad</a>
                        </div>
                    </article>
                @empty
                    <div class="empty-list">Aun no hay propiedades destacadas.</div>
                @endforelse
            </div>
        </section>

        <section class="sec" id="props">
            <div class="shead">
                <div>
                    <p class="eyebrow">Disponibles ahora</p>
                    <h2 class="stitle">Todas las propiedades</h2>
                    <p class="ssub">Catalogo principal organizado por tipo, ubicacion y filtros reales.</p>
                </div>
                <div>
                    <a href="{{ route('propiedades.create') }}" class="btn btn-main">+ Publicar gratis</a>
                    <a href="{{ route('home') }}#props" class="btn btn-outline">Limpiar filtros</a>
                </div>
            </div>

            @if ($propiedades->isEmpty())
                <div class="empty-list">No encontramos propiedades con esos filtros. Prueba con otros criterios.</div>
            @else
                <div class="prop-grid">
                    @foreach ($propiedades as $propiedad)
                        <article class="card">
                            @if ($propiedad->portadaImagen)
                                <img class="card-cover" src="{{ route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen]) }}" alt="Portada de {{ $propiedad->titulo }}">
                            @else
                                <div class="card-empty">Sin foto</div>
                            @endif
                            <div class="card-body">
                                <p class="card-type">{{ $propiedad->tipoPropiedad?->nombre ?? 'Propiedad' }}</p>
                                <h3 class="card-title">{{ $propiedad->titulo }}</h3>
                                <p class="card-loc">
                                    {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                                    {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                                </p>
                                <p class="card-price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                                <div class="meta-row">
                                    <span class="meta-chip">{{ ucfirst($propiedad->tipo) }}</span>
                                    <span class="meta-chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                                    <span class="meta-chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                                </div>
                                <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">Ver detalle</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($propiedades->lastPage() > 1)
                    <nav class="pager" aria-label="Paginacion de propiedades">
                        @if ($propiedades->onFirstPage())
                            <span>&lt;</span>
                        @else
                            <a href="{{ $propiedades->previousPageUrl() }}">&lt;</a>
                        @endif

                        @for ($page = 1; $page <= $propiedades->lastPage(); $page++)
                            @if ($page === $propiedades->currentPage())
                                <span class="current">{{ $page }}</span>
                            @else
                                <a href="{{ $propiedades->url($page) }}">{{ $page }}</a>
                            @endif
                        @endfor

                        @if ($propiedades->hasMorePages())
                            <a href="{{ $propiedades->nextPageUrl() }}">&gt;</a>
                        @else
                            <span>&gt;</span>
                        @endif
                    </nav>
                @endif
            @endif
        </section>

        <section class="ciu-sec sec" id="ciudades">
            <div class="shead">
                <div>
                    <p class="eyebrow">Destinos</p>
                    <h2 class="stitle">Ciudades con mas oportunidades</h2>
                    <p class="ssub">Mercados inmobiliarios activos en selva y sierra.</p>
                </div>
            </div>
            <div class="ciu-grid">
                @forelse ($ciudadesTop->take(8) as $ciudad)
                    <article class="city-card">
                        <h3>{{ ucwords(mb_strtolower($ciudad->distrito)) }}</h3>
                        <p>{{ $ciudad->propiedades_count }} propiedades en {{ ucwords(mb_strtolower($ciudad->departamento)) }}</p>
                    </article>
                @empty
                    <article class="city-card">
                        <h3>Sin datos</h3>
                        <p>Aun no hay propiedades disponibles para mostrar ciudades.</p>
                    </article>
                @endforelse
            </div>
        </section>

        <section class="sec" id="publicar">
            <div class="shead">
                <div>
                    <p class="eyebrow">Como publicar</p>
                    <h2 class="stitle">Publica en 4 pasos</h2>
                    <p class="ssub">Flujo claro para nuevos usuarios.</p>
                </div>
            </div>
            <div class="guia-grid">
                <article class="step"><strong>1.</strong><h3>Crea tu cuenta</h3><p>Registro rapido con correo y telefono.</p></article>
                <article class="step"><strong>2.</strong><h3>Sube fotos</h3><p>Publica imagenes claras de tu propiedad.</p></article>
                <article class="step"><strong>3.</strong><h3>Completa datos</h3><p>Precio, metraje, ubicacion y detalles.</p></article>
                <article class="step"><strong>4.</strong><h3>Publica y recibe contactos</h3><p>Conecta con compradores de forma directa.</p></article>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-wrap">
            <a href="{{ route('home') }}" class="logo"><span class="logo-icon">&#127807;</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <p class="foot-note">Portal inmobiliario de la selva y sierra peruana.</p>
        </div>
    </footer>
    <script>
        (() => {
            const wrap = document.querySelector('[data-cat-wrap]');
            const list = document.querySelector('[data-cat-list]');
            const toggle = document.querySelector('[data-cat-toggle]');
            if (!wrap || !list || !toggle) {
                return;
            }

            const mobileQuery = window.matchMedia('(max-width: 760px)');

            const setCollapsed = () => {
                wrap.classList.remove('is-expanded');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.textContent = 'Ver mas categorias';
            };

            const updateToggleVisibility = () => {
                if (!mobileQuery.matches) {
                    wrap.classList.remove('has-hidden');
                    setCollapsed();
                    return;
                }

                const hasHiddenItems = list.scrollWidth > (list.clientWidth + 4);
                if (hasHiddenItems || wrap.classList.contains('is-expanded')) {
                    wrap.classList.add('has-hidden');
                } else {
                    wrap.classList.remove('has-hidden');
                    setCollapsed();
                }
            };

            toggle.addEventListener('click', () => {
                const expand = !wrap.classList.contains('is-expanded');
                wrap.classList.toggle('is-expanded', expand);
                toggle.setAttribute('aria-expanded', expand ? 'true' : 'false');
                toggle.textContent = expand ? 'Ver menos categorias' : 'Ver mas categorias';
            });

            window.addEventListener('resize', updateToggleVisibility);
            updateToggleVisibility();
        })();
    </script>
</body>
</html>
