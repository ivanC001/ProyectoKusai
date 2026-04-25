<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kusay.pe | Portal inmobiliario del Perú</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;0,800;0,900;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --g1: #1a4731;
            --g2: #2d7a52;
            --g3: #52b788;
            --a1: #b5451b;
            --n0: #0d1f15;
            --n3: #4a6b55;
            --n6: #e4ede6;
            --n7: #f2f7f3;
            --n8: #fafcfa;
            --gold: #d4a017;
            --r8: 8px;
            --r12: 12px;
            --r16: 16px;
            --r100: 100px;
            --sh1: 0 2px 12px rgba(13, 31, 21, .08);
            --sh2: 0 14px 44px rgba(13, 31, 21, .18);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--n8);
            color: var(--n0);
            line-height: 1.5;
        }
        a { text-decoration: none; color: inherit; }
        img { width: 100%; display: block; }

        .sec {
            padding: 64px 48px;
        }

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
            color: var(--g2);
            font-weight: 800;
            margin-bottom: 6px;
        }

        .stitle {
            font-family: 'Fraunces', serif;
            font-size: clamp(1.8rem, 3vw, 2.4rem);
            line-height: 1.15;
        }

        .ssub {
            color: var(--n3);
            max-width: 620px;
        }

        .btn {
            border: none;
            border-radius: var(--r8);
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-g { background: var(--g1); color: #fff; }
        .btn-out { border: 1.5px solid var(--g2); color: var(--g2); background: transparent; }
        .btn-wsp { background: #25d366; color: #fff; }

        /* Header / Logo */
        #nav {
            position: sticky;
            top: 0;
            z-index: 40;
            height: 68px;
            padding: 0 42px;
            background: rgba(250, 252, 250, .95);
            border-bottom: 1px solid var(--n6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(16px);
        }

        .logo {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            letter-spacing: -.3px;
            gap: 4px;
        }
        .logo .leaf { display: inline-block; animation: sway 3s ease-in-out infinite; }
        .logo .dot { color: #e07a44; }
        .logo .pe { color: var(--g2); }
        @keyframes sway {
            0%, 100% { transform: rotate(-10deg); }
            50% { transform: rotate(10deg); }
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .nav-links a { color: var(--n3); font-weight: 600; font-size: .9rem; }
        .nav-actions { display: flex; align-items: center; gap: 8px; }

        /* Hero */
        .hero {
            min-height: 86vh;
            padding: 90px 20px 50px;
            background: linear-gradient(160deg, #0a1f12 0%, #1a4731 42%, #2d7a52 76%, #163320 100%);
            color: #fff;
            text-align: center;
        }
        .hero-inner { max-width: 860px; margin: 0 auto; }
        .hero-tag {
            display: inline-flex;
            border-radius: var(--r100);
            border: 1px solid rgba(83, 183, 136, .45);
            background: rgba(83, 183, 136, .2);
            color: #b9efd4;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 700;
            padding: 6px 14px;
            margin-bottom: 18px;
        }
        .hero h1 {
            font-family: 'Fraunces', serif;
            font-size: clamp(2.1rem, 5vw, 3.9rem);
            line-height: 1.08;
            margin-bottom: 12px;
        }
        .hero h1 em { color: var(--g3); font-style: italic; }
        .hero-sub {
            max-width: 680px;
            margin: 0 auto 22px;
            color: rgba(255, 255, 255, .78);
        }

        .search-box {
            max-width: 760px;
            margin: 0 auto;
            border-radius: var(--r16);
            background: #fff;
            color: var(--n0);
            box-shadow: 0 22px 52px rgba(0, 0, 0, .35);
            overflow: hidden;
        }
        .search-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid var(--n6);
        }
        .search-tabs button {
            border: none;
            background: transparent;
            padding: 13px 10px;
            font-weight: 700;
            color: #6f8a7c;
        }
        .search-tabs .active {
            color: var(--g1);
            background: rgba(26, 71, 49, .04);
            border-bottom: 3px solid var(--g1);
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
            border: 1.5px solid var(--n6);
            background: var(--n7);
            border-radius: var(--r8);
            padding: 10px 12px;
            font: inherit;
            color: var(--n0);
        }
        .search-btn {
            border: none;
            border-radius: var(--r8);
            background: var(--g1);
            color: #fff;
            padding: 11px 16px;
            font-weight: 700;
        }

        /* Explorador */
        .ad-top {
            background: linear-gradient(135deg, #fffbec, #fff4cc);
            border-bottom: 1px solid #f0d875;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            padding: 12px 48px;
        }
        .ad-lbl {
            background: rgba(212, 160, 23, .18);
            color: #9c7813;
            border-radius: var(--r100);
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 4px 10px;
        }
        .ad-txt { font-size: .9rem; color: var(--n0); }
        .ad-cta { background: var(--gold); color: var(--n0); }

        .ttabs {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            padding: 12px 24px;
            border-bottom: 1px solid var(--n6);
            background: #fff;
        }
        .ttab {
            border: 1px solid var(--n6);
            background: #fff;
            color: var(--n3);
            border-radius: var(--r100);
            padding: 9px 14px;
            font-weight: 600;
            font-size: .84rem;
        }
        .ttab.on { background: var(--g1); color: #fff; border-color: var(--g1); }

        /* Destacadas / Propiedades */
        .vip-sec { background: linear-gradient(180deg, #fffbed, #fff8e0); }
        .vip-grid, .prop-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .card {
            background: #fff;
            border: 1.5px solid var(--n6);
            border-radius: var(--r16);
            overflow: hidden;
            box-shadow: var(--sh1);
        }
        .card-body { padding: 12px 14px 14px; }
        .card-type { color: var(--g2); font-weight: 700; text-transform: uppercase; font-size: .67rem; }
        .card-title { font-family: 'Fraunces', serif; font-size: 1rem; margin: 4px 0; }
        .card-loc { color: #6f8a7c; font-size: .82rem; margin-bottom: 10px; }
        .card-price { color: var(--a1); font-family: 'Fraunces', serif; font-size: 1.2rem; font-weight: 800; }

        /* Ciudades */
        .ciu-sec { background: var(--n0); color: #fff; }
        .ciu-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: repeat(2, 180px);
            gap: 12px;
        }
        .city-card {
            position: relative;
            border-radius: var(--r16);
            overflow: hidden;
        }
        .city-card:first-child { grid-row: 1 / 3; }
        .city-card img { height: 100%; object-fit: cover; filter: brightness(.64); }
        .city-ov {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13, 31, 21, .86) 0%, transparent 55%);
            display: flex;
            flex-direction: column;
            justify-content: end;
            padding: 16px;
        }
        .city-name {
            font-family: 'Fraunces', serif;
            font-size: 1.2rem;
            margin-bottom: 2px;
        }
        .city-count { color: rgba(255, 255, 255, .75); font-size: .78rem; }

        /* Guia */
        .guia-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        .step {
            background: #fff;
            border: 1px solid var(--n6);
            border-radius: var(--r16);
            padding: 18px;
        }
        .step h3 { font-family: 'Fraunces', serif; margin: 8px 0; font-size: 1.02rem; }
        .step p { color: var(--n3); font-size: .9rem; }

        footer {
            background: #102a1d;
            color: #d5e4db;
            padding: 36px 48px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }
        .footer-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        footer .logo { color: #fff; }
        .foot-note { color: #9fb8aa; font-size: .85rem; }

        @media (max-width: 1100px) {
            .nav-links { display: none; }
            .vip-grid, .prop-grid { grid-template-columns: repeat(2, 1fr); }
            .guia-grid { grid-template-columns: repeat(2, 1fr); }
            .ciu-grid { grid-template-columns: 1fr 1fr; grid-template-rows: repeat(3, 170px); }
            .city-card:first-child { grid-row: auto; grid-column: 1 / 3; }
        }
        @media (max-width: 760px) {
            #nav { padding: 0 16px; }
            .nav-actions { display: none; }
            .sec { padding: 44px 16px; }
            .ad-top { padding: 12px 16px; }
            .row, .row2 { grid-template-columns: 1fr; }
            .vip-grid, .prop-grid, .guia-grid, .ciu-grid { grid-template-columns: 1fr; }
            .city-card:first-child { grid-column: auto; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="nav">
        <a href="#" class="logo"><span class="leaf">🌿</span>&nbsp;Kusay<span class="dot">.</span><span class="pe">pe</span></a>
        <ul class="nav-links">
            <li><a href="#props">Propiedades</a></li>
            <li><a href="#ciudades">Ciudades</a></li>
            <li><a href="#publicar">Cómo publicar</a></li>
            <li><a href="#destacadas">Destacadas</a></li>
        </ul>
        <div class="nav-actions">
            <button class="btn btn-wsp">WhatsApp</button>
            <button class="btn btn-out">Ingresar</button>
            <button class="btn btn-g">Registrarse</button>
        </div>
    </header>

    <main>
        <!-- Hero -->
        <section class="hero" id="inicio">
            <div class="hero-inner">
                <div class="hero-tag">Portal inmobiliario N°1 · Selva y Sierra peruana</div>
                <h1>Tu próxima propiedad en la <em>Selva</em> y <em>Sierra</em> del Perú</h1>
                <p class="hero-sub">Terrenos, casas, departamentos, lotes, chacras y más. Publica gratis y conecta directo con compradores.</p>

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
                            <input type="number" placeholder="Precio mín. (S/.)">
                            <input type="number" placeholder="Precio máx. (S/.)">
                            <input type="number" placeholder="Área mín. (m²)">
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

        <!-- Explorador -->
        <section>
            <div class="ad-top">
                <span class="ad-lbl">Publicidad</span>
                <span class="ad-txt"><strong>CONSTRUCTORA AMAZÓNICA S.A.C.</strong> — Lotes en Yarinacocha desde S/. 25,000.</span>
                <button class="btn ad-cta">Ver oferta</button>
            </div>
            <div class="ttabs">
                <button class="ttab on">Todos</button>
                <button class="ttab">Terrenos</button>
                <button class="ttab">Casas</button>
                <button class="ttab">Departamentos</button>
                <button class="ttab">Lotes</button>
                <button class="ttab">Chacras</button>
            </div>
        </section>

        <!-- Destacadas -->
        <section class="vip-sec sec" id="destacadas">
            <div class="shead">
                <div>
                    <p class="eyebrow">Sección VIP</p>
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
                        <h3 class="card-title">Departamento en zona céntrica</h3>
                        <p class="card-loc">Huancayo, Junín</p>
                        <p class="card-price">S/. 210,000</p>
                    </div>
                </article>
            </div>
        </section>

        <!-- Propiedades -->
        <section class="sec" id="props">
            <div class="shead">
                <div>
                    <p class="eyebrow">Disponibles ahora</p>
                    <h2 class="stitle">Todas las propiedades</h2>
                    <p class="ssub">Catálogo principal organizado por tipo y ubicación.</p>
                </div>
                <div>
                    <button class="btn btn-g">+ Publicar gratis</button>
                    <button class="btn btn-out">Limpiar filtros</button>
                </div>
            </div>
            <div class="prop-grid">
                <article class="card"><img src="https://images.unsplash.com/photo-1464082354059-27db6ce50048?w=800&q=80" alt="Lote"><div class="card-body"><p class="card-type">Lote</p><h3 class="card-title">Lote urbano habilitado</h3><p class="card-loc">Tarapoto, San Martín</p><p class="card-price">S/. 38,000</p></div></article>
                <article class="card"><img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&q=80" alt="Local"><div class="card-body"><p class="card-type">Local</p><h3 class="card-title">Local comercial en avenida principal</h3><p class="card-loc">Pucallpa, Ucayali</p><p class="card-price">S/. 320,000</p></div></article>
                <article class="card"><img src="https://images.unsplash.com/photo-1476842634003-7dcca8f832de?w=800&q=80" alt="Chacra"><div class="card-body"><p class="card-type">Chacra</p><h3 class="card-title">Parcela agrícola con cacao</h3><p class="card-loc">Tingo María, Huánuco</p><p class="card-price">S/. 45,000</p></div></article>
            </div>
        </section>

        <!-- Ciudades -->
        <section class="ciu-sec sec" id="ciudades">
            <div class="shead">
                <div>
                    <p class="eyebrow">Destinos</p>
                    <h2 class="stitle">Ciudades con más oportunidades</h2>
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
                    <img src="https://images.unsplash.com/photo-1476842634003-7dcca8f832de?w=700&q=80" alt="Huánuco">
                    <div class="city-ov"><h3 class="city-name">Huánuco</h3><p class="city-count">29 propiedades</p></div>
                </article>
                <article class="city-card">
                    <img src="https://images.unsplash.com/photo-1534430480872-3498386e7856?w=700&q=80" alt="Huancayo">
                    <div class="city-ov"><h3 class="city-name">Huancayo</h3><p class="city-count">52 propiedades</p></div>
                </article>
            </div>
        </section>

        <!-- Publicar -->
        <section class="sec" id="publicar">
            <div class="shead">
                <div>
                    <p class="eyebrow">Cómo publicar</p>
                    <h2 class="stitle">Publica en 4 pasos</h2>
                    <p class="ssub">Flujo principal más claro para nuevos usuarios.</p>
                </div>
            </div>
            <div class="guia-grid">
                <article class="step"><strong>1.</strong><h3>Crea tu cuenta</h3><p>Registro rápido con correo y WhatsApp.</p></article>
                <article class="step"><strong>2.</strong><h3>Sube fotos</h3><p>Publica imágenes claras de tu propiedad.</p></article>
                <article class="step"><strong>3.</strong><h3>Completa datos</h3><p>Precio, metraje, ubicación y documento.</p></article>
                <article class="step"><strong>4.</strong><h3>Publica y recibe contactos</h3><p>Te escriben directo por WhatsApp o correo.</p></article>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-wrap">
            <a href="#" class="logo"><span class="leaf">🌿</span>&nbsp;Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <p class="foot-note">Portal inmobiliario de la selva y sierra peruana · Pucallpa, Ucayali</p>
        </div>
    </footer>
</body>
</html>
