<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kusay.pe | Portal inmobiliario</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite('resources/css/pages/welcome.css')
</head>
<body>
    <header id="nav">
        <a href="#" class="logo"><span class="logo-icon">ðŸŒ¿</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
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
                                <img src="{{ route('profile.photo', ['v' => optional(auth()->user()->updated_at)->timestamp]) }}" alt="Foto">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </span>
                        <span class="user-fullname">{{ trim(auth()->user()->name.' '.auth()->user()->apellidos) }}</span>
                        <span class="user-caret">â–¼</span>
                    </summary>
                    <div class="user-dropdown">
                        <a href="{{ route('propiedades.mine') }}" class="user-item">Mis publicaciones</a>
                        @if (auth()->user()->esAdmin())
                            <a href="{{ route('admin.PanelAdministrativo') }}" class="user-item">Panel administrativo</a>
                        @endif
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
            <button class="cat-btn active" type="button"><span class="cat-ic">ðŸ </span>Todos</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸŒ±</span>Terrenos</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸ¡</span>Casas</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸ¢</span>Departamentos</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸ“</span>Lotes</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸª</span>Locales</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸŒ¾</span>Chacras</button>
            <button class="cat-btn" type="button"><span class="cat-ic">ðŸ’¼</span>Oficinas</button>
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
            <a href="#" class="logo"><span class="logo-icon">ðŸŒ¿</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
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

