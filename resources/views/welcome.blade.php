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
            --gold: #d1a51f;
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
        button { font-family: inherit; }

        .btn {
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;
        }
        .btn:hover { transform: translateY(-1px); }

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
            gap: 5px;
        }
        .logo-icon {
            font-size: 1.04em;
            line-height: 1;
            filter: drop-shadow(0 2px 1px rgba(8, 39, 26, .18));
            animation: sway 2.8s ease-in-out infinite;
        }
        .logo .dot { color: #df8347; }
        .logo .pe { color: var(--green-700); }
        @keyframes sway {
            0%, 100% { transform: rotate(-8deg); }
            50% { transform: rotate(8deg); }
        }

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
            position: relative;
            transition: color .2s ease;
        }
        .nav-links a::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 2px;
            background: linear-gradient(90deg, var(--green-700), var(--green-500));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .2s ease;
        }
        .nav-links a:hover,
        .nav-links a.active { color: var(--green-900); }
        .nav-links a:hover::after,
        .nav-links a.active::after { transform: scaleX(1); }

        .nav-actions {
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

        .quick-nav {
            position: sticky;
            top: 76px;
            z-index: 35;
            border-bottom: 1px solid var(--line);
            box-shadow: 0 8px 16px rgba(14, 45, 32, .08);
        }

        .quick-categories {
            background: #f6faf8;
            border-bottom: 1px solid var(--line);
            padding: 10px 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .cat-btn {
            border: none;
            background: transparent;
            color: #6a897b;
            font-size: 1.02rem;
            font-weight: 700;
            border-radius: 999px;
            padding: 8px 14px;
            cursor: pointer;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .cat-ic {
            font-size: 1rem;
            line-height: 1;
            filter: drop-shadow(0 1px 0 rgba(0, 0, 0, .18));
        }
        .cat-btn:hover {
            color: var(--green-800);
            background: rgba(63, 177, 115, .12);
        }
        .cat-btn.active {
            color: var(--green-900);
            box-shadow: inset 0 -3px 0 var(--green-900);
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
            transition: all .2s ease;
        }
        .chip:hover {
            border-color: #92b9a4;
            transform: translateY(-1px);
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
        .ord-select {
            border: 1px solid #cfdad3;
            border-radius: 10px;
            padding: 9px 12px;
            min-width: 170px;
            background: #fff;
            color: #315947;
            font: inherit;
        }
        .count { color: #6a8678; font-weight: 600; }
        .view-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #cfdad3;
            background: #fff;
            color: #2a4f3e;
            font-weight: 800;
            cursor: pointer;
        }
        .view-btn.active {
            background: var(--green-900);
            color: #fff;
            border-color: var(--green-900);
        }

        .hero {
            min-height: 78vh;
            padding: 96px 20px 54px;
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
            max-width: 800px;
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
        .search-tabs button {
            border: none;
            background: #fff;
            color: #729082;
            font-weight: 700;
            padding: 14px 8px;
            cursor: pointer;
        }
        .search-tabs button.active {
            color: var(--green-900);
            border-bottom: 3px solid var(--green-900);
            background: rgba(21, 85, 58, .05);
        }
        .search-body { padding: 16px; }
        .row {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 8px;
            margin-bottom: 8px;
        }
        .row2 {
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
        .card-body { padding: 12px 14px 14px; }
        .card-type { color: var(--green-700); font-weight: 700; text-transform: uppercase; font-size: .68rem; }
        .card-title { font-family: "Fraunces", serif; font-size: 1rem; margin: 4px 0; }
        .card-loc { color: #6a8678; font-size: .82rem; margin-bottom: 10px; }
        .card-price { color: #b04e27; font-family: "Fraunces", serif; font-size: 1.2rem; font-weight: 800; }

        .ciu-sec { background: #0f2319; color: #fff; }
        .ciu-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: repeat(2, 180px);
            gap: 12px;
        }
        .city-card { position: relative; border-radius: var(--radius); overflow: hidden; }
        .city-card:first-child { grid-row: 1 / 3; }
        .city-card img { height: 100%; object-fit: cover; filter: brightness(.64); }
        .city-ov {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(11, 24, 18, .86) 0%, transparent 55%);
            display: flex;
            flex-direction: column;
            justify-content: end;
            padding: 16px;
        }
        .city-name { font-family: "Fraunces", serif; font-size: 1.2rem; margin-bottom: 2px; }
        .city-count { color: rgba(255, 255, 255, .76); font-size: .78rem; }

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
            .guia-grid { grid-template-columns: repeat(2, 1fr); }
            .ciu-grid { grid-template-columns: 1fr 1fr; grid-template-rows: repeat(3, 170px); }
            .city-card:first-child { grid-row: auto; grid-column: 1 / 3; }
        }

        @media (max-width: 760px) {
            #nav { padding: 0 14px; }
            .nav-actions { display: none; }
            .quick-nav { top: 76px; }
            .quick-categories { justify-content: flex-start; overflow-x: auto; flex-wrap: nowrap; }
            .quick-right { width: 100%; margin-left: 0; justify-content: flex-start; }
            .sec { padding: 44px 16px; }
            .row, .row2 { grid-template-columns: 1fr; }
            .vip-grid, .prop-grid, .guia-grid, .ciu-grid { grid-template-columns: 1fr; }
            .city-card:first-child { grid-column: auto; }
        }
    </style>
</head>
<body>
    <header id="nav">
        <a href="#" class="logo"><span class="logo-icon">🌿</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
        <ul class="nav-links">
            <li><a class="active" href="#props">Propiedades</a></li>
            <li><a href="#ciudades">Ciudades</a></li>
            <li><a href="#publicar">Como publicar</a></li>
            <li><a href="#destacadas">Destacadas</a></li>
        </ul>
        <div class="nav-actions">
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
                <a href="{{ route('propiedades.create') }}" class="btn btn-main">Publica gratis</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Iniciar sesion</a>
                <a href="{{ route('register') }}" class="btn btn-main">Publica gratis</a>
            @endauth
        </div>
    </header>

    <section class="quick-nav">
        <div class="quick-categories">
            <button class="cat-btn active" type="button"><span class="cat-ic">🏠</span>Todos</button>
            <button class="cat-btn" type="button"><span class="cat-ic">🌱</span>Terrenos</button>
            <button class="cat-btn" type="button"><span class="cat-ic">🏡</span>Casas</button>
            <button class="cat-btn" type="button"><span class="cat-ic">🏢</span>Departamentos</button>
            <button class="cat-btn" type="button"><span class="cat-ic">📐</span>Lotes</button>
            <button class="cat-btn" type="button"><span class="cat-ic">🏪</span>Locales</button>
            <button class="cat-btn" type="button"><span class="cat-ic">🌾</span>Chacras</button>
            <button class="cat-btn" type="button"><span class="cat-ic">💼</span>Oficinas</button>
        </div>
        <div class="quick-filters">
            <div class="chip-row">
                <button class="chip active" type="button">Todo el Peru</button>
                <button class="chip" type="button">Pucallpa</button>
                <button class="chip" type="button">Tarapoto</button>
                <button class="chip" type="button">Iquitos</button>
                <button class="chip" type="button">Huanuco</button>
                <button class="chip" type="button">Huancayo</button>
                <button class="chip" type="button">Tingo Maria</button>
                <button class="chip" type="button">Lima</button>
            </div>
            <div class="quick-right">
                <select class="ord-select" aria-label="Ordenar">
                    <option>Ordenar</option>
                    <option>Mas recientes</option>
                    <option>Menor precio</option>
                    <option>Mayor precio</option>
                </select>
                <span class="count">14 propiedades</span>
                <button class="view-btn active" type="button">#</button>
                <button class="view-btn" type="button">=</button>
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
                        <button class="active" type="button">Comprar</button>
                        <button type="button">Alquilar</button>
                        <button type="button">Proyectos</button>
                    </div>
                    <div class="search-body">
                        <div class="row">
                            <input type="text" placeholder="Ciudad, zona o referencia...">
                            <select>
                                <option>Todo tipo</option>
                                <option>Terreno</option>
                                <option>Casa</option>
                                <option>Departamento</option>
                                <option>Lote</option>
                            </select>
                            <button class="search-btn" type="button">Buscar</button>
                        </div>
                        <div class="row2">
                            <input type="number" placeholder="Precio min. (S/.)">
                            <input type="number" placeholder="Precio max. (S/.)">
                            <input type="number" placeholder="Area min. (m2)">
                            <select>
                                <option>Dormitorios</option>
                                <option>1+</option>
                                <option>2+</option>
                                <option>3+</option>
                            </select>
                        </div>
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
                <article class="card">
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80" alt="Terreno en Yarinacocha">
                    <div class="card-body">
                        <p class="card-type">Terreno</p>
                        <h3 class="card-title">Terreno esquinero en Yarinacocha</h3>
                        <p class="card-loc">Pucallpa, Ucayali</p>
                        <p class="card-price">S/. 85,000</p>
                    </div>
                </article>
                <article class="card">
                    <img src="https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&q=80" alt="Casa en Pucallpa">
                    <div class="card-body">
                        <p class="card-type">Casa</p>
                        <h3 class="card-title">Casa moderna de 2 pisos</h3>
                        <p class="card-loc">Pucallpa, Ucayali</p>
                        <p class="card-price">S/. 180,000</p>
                    </div>
                </article>
                <article class="card">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80" alt="Departamento en Huancayo">
                    <div class="card-body">
                        <p class="card-type">Departamento</p>
                        <h3 class="card-title">Departamento en zona centrica</h3>
                        <p class="card-loc">Huancayo, Junin</p>
                        <p class="card-price">S/. 210,000</p>
                    </div>
                </article>
            </div>
        </section>

        <section class="sec" id="props">
            <div class="shead">
                <div>
                    <p class="eyebrow">Disponibles ahora</p>
                    <h2 class="stitle">Todas las propiedades</h2>
                    <p class="ssub">Catalogo principal organizado por tipo y ubicacion.</p>
                </div>
                <div>
                    <a href="{{ route('propiedades.create') }}" class="btn btn-main">+ Publicar gratis</a>
                    <button class="btn btn-outline">Limpiar filtros</button>
                </div>
            </div>
            <div class="prop-grid">
                <article class="card"><img src="https://images.unsplash.com/photo-1464082354059-27db6ce50048?w=800&q=80" alt="Lote"><div class="card-body"><p class="card-type">Lote</p><h3 class="card-title">Lote urbano habilitado</h3><p class="card-loc">Tarapoto, San Martin</p><p class="card-price">S/. 38,000</p></div></article>
                <article class="card"><img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&q=80" alt="Local"><div class="card-body"><p class="card-type">Local</p><h3 class="card-title">Local comercial en avenida principal</h3><p class="card-loc">Pucallpa, Ucayali</p><p class="card-price">S/. 320,000</p></div></article>
                <article class="card"><img src="https://images.unsplash.com/photo-1476842634003-7dcca8f832de?w=800&q=80" alt="Chacra"><div class="card-body"><p class="card-type">Chacra</p><h3 class="card-title">Parcela agricola con cacao</h3><p class="card-loc">Tingo Maria, Huanuco</p><p class="card-price">S/. 45,000</p></div></article>
            </div>
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
                <article class="city-card">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900&q=80" alt="Pucallpa">
                    <div class="city-ov"><h3 class="city-name">Pucallpa</h3><p class="city-count">64 propiedades</p></div>
                </article>
                <article class="city-card">
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=700&q=80" alt="Tarapoto">
                    <div class="city-ov"><h3 class="city-name">Tarapoto</h3><p class="city-count">41 propiedades</p></div>
                </article>
                <article class="city-card">
                    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=700&q=80" alt="Iquitos">
                    <div class="city-ov"><h3 class="city-name">Iquitos</h3><p class="city-count">38 propiedades</p></div>
                </article>
                <article class="city-card">
                    <img src="https://images.unsplash.com/photo-1476842634003-7dcca8f832de?w=700&q=80" alt="Huanuco">
                    <div class="city-ov"><h3 class="city-name">Huanuco</h3><p class="city-count">29 propiedades</p></div>
                </article>
                <article class="city-card">
                    <img src="https://images.unsplash.com/photo-1534430480872-3498386e7856?w=700&q=80" alt="Huancayo">
                    <div class="city-ov"><h3 class="city-name">Huancayo</h3><p class="city-count">52 propiedades</p></div>
                </article>
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
            <a href="#" class="logo"><span class="logo-icon">🌿</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <p class="foot-note">Portal inmobiliario de la selva y sierra peruana - Pucallpa, Ucayali</p>
        </div>
    </footer>

    <script>
        const toggleGroup = (selector) => {
            document.querySelectorAll(selector).forEach((button) => {
                button.addEventListener('click', () => {
                    document.querySelectorAll(selector).forEach((item) => item.classList.remove('active'));
                    button.classList.add('active');
                });
            });
        };

        toggleGroup('.cat-btn');
        toggleGroup('.chip');
        toggleGroup('.view-btn');
    </script>
</body>
</html>
