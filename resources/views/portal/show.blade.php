<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $propiedad->titulo }} | Kusay.pe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <style>
        :root {
            --bg: #f2f7f4;
            --line: #d3e0d8;
            --ink: #163f30;
            --soft: #5f7f71;
            --brand: #16573c;
            --accent: #b04e27;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background: var(--bg);
        }
        .wrap {
            width: min(1080px, 94vw);
            margin: 0 auto;
            padding: 24px 0 42px;
        }
        .top {
            margin-bottom: 14px;
        }
        .crumb {
            text-decoration: none;
            color: #628274;
            font-weight: 700;
            font-size: .9rem;
        }
        .title {
            margin: 8px 0 6px;
            font-family: "Fraunces", serif;
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            line-height: 1.08;
        }
        .subtitle {
            margin: 0;
            color: var(--soft);
        }
        .layout {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 14px;
        }
        .panel {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 16px 28px rgba(8, 42, 29, .09);
            overflow: hidden;
        }
        .cover {
            width: 100%;
            height: 330px;
            object-fit: cover;
            background: #dde9e2;
        }
        .content {
            padding: 16px;
        }
        .price {
            margin: 0 0 8px;
            color: var(--accent);
            font-family: "Fraunces", serif;
            font-size: clamp(1.4rem, 2.8vw, 2rem);
        }
        .meta {
            margin: 0 0 10px;
            color: var(--soft);
        }
        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }
        .chip {
            border-radius: 999px;
            border: 1px solid #ccd9d2;
            background: #f4f9f6;
            color: #355b4b;
            font-size: .75rem;
            font-weight: 800;
            padding: 4px 8px;
        }
        .desc {
            margin: 0;
            color: #355b4b;
            line-height: 1.65;
            white-space: pre-line;
        }
        .fav-row {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }
        .fav-btn {
            border: 1px solid #c5d5cc;
            border-radius: 999px;
            background: #f6faf8;
            color: #255340;
            font: inherit;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1;
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            cursor: pointer;
            transition: .2s ease;
        }
        .fav-btn.active {
            border-color: #16573c;
            background: #16573c;
            color: #fff;
        }
        .fav-btn:hover {
            transform: translateY(-1px);
        }
        .fav-feedback {
            margin: 0;
            color: #355b4b;
            font-size: .85rem;
            font-weight: 700;
        }
        .fav-login {
            margin: 10px 0 0;
            color: var(--soft);
            font-size: .88rem;
        }
        .fav-login a {
            color: #1b6c49;
            font-weight: 800;
        }
        .map-panel {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 16px 28px rgba(8, 42, 29, .09);
            padding: 14px;
        }
        .map-title {
            margin: 0 0 4px;
            font-size: 1.1rem;
            font-weight: 800;
        }
        .map-subtitle {
            margin: 0 0 10px;
            color: var(--soft);
            font-size: .9rem;
        }
        #mapa-detalle {
            width: 100%;
            height: 360px;
            border: 1px solid #bfd6c8;
            border-radius: 12px;
            overflow: hidden;
        }
        .coords {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .coord {
            border-radius: 999px;
            border: 1px solid #c8ddd1;
            background: #eef6f1;
            color: #2f5d4b;
            font-size: .78rem;
            font-weight: 800;
            padding: 5px 10px;
        }
        .warn {
            margin-top: 8px;
            color: #7a5b00;
            background: #fff7e2;
            border: 1px solid #efdca8;
            border-radius: 10px;
            font-size: .84rem;
            font-weight: 700;
            padding: 8px 10px;
        }
        @media (max-width: 900px) {
            .layout {
                grid-template-columns: 1fr;
            }
            .cover {
                height: 250px;
            }
            #mapa-detalle {
                height: 290px;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header class="top">
            <a class="crumb" href="{{ route('home') }}">Volver al inicio</a>
            <h1 class="title">{{ $propiedad->titulo }}</h1>
            <p class="subtitle">{{ $propiedad->direccion }}</p>
        </header>

        <div class="layout">
            <article class="panel">
                @if ($propiedad->portadaImagen)
                    <img class="cover" src="{{ route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen]) }}" alt="Portada de {{ $propiedad->titulo }}">
                @else
                    <div class="cover"></div>
                @endif

                <div class="content">
                    <p class="price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                    <p class="meta">
                        {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                        {{ $propiedad->ubicacion?->provincia ?? 'Sin provincia' }},
                        {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                    </p>
                    <div class="chips">
                        <span class="chip">{{ ucfirst($propiedad->tipo) }}</span>
                        <span class="chip">{{ $propiedad->tipoPropiedad?->nombre ?? 'Propiedad' }}</span>
                        <span class="chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                        <span class="chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                        <span class="chip">{{ $propiedad->visitas_count }} clic(s)</span>
                        <span class="chip" id="detalle-favoritos-chip">{{ $propiedad->favoritos_count }} favorito(s)</span>
                    </div>
                    <p class="desc">{{ $propiedad->descripcion }}</p>
                    @auth
                        <div class="fav-row">
                            <button
                                id="detalle-fav-btn"
                                class="fav-btn {{ $esFavorita ? 'active' : '' }}"
                                type="button"
                                data-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                data-favorita="{{ $esFavorita ? '1' : '0' }}"
                                aria-label="{{ $esFavorita ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                aria-pressed="{{ $esFavorita ? 'true' : 'false' }}"
                                title="{{ $esFavorita ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                            >
                                {{ $esFavorita ? '★' : '☆' }}
                            </button>
                            <p class="fav-feedback" id="detalle-fav-feedback"></p>
                        </div>
                    @else
                        <p class="fav-login">
                            <a href="{{ route('login') }}">Inicia sesion</a> para guardar esta propiedad en favoritos.
                        </p>
                    @endauth
                </div>
            </article>

            <aside class="map-panel">
                <h2 class="map-title">Ubicacion en mapa</h2>
                <p class="map-subtitle">Visualizacion con OpenStreetMap y marcador en la ubicacion registrada.</p>
                <div id="mapa-detalle" data-lat="{{ $propiedad->latitud }}" data-lng="{{ $propiedad->longitud }}"></div>
                <div class="coords">
                    <span class="coord">Lat: {{ $propiedad->latitud !== null ? number_format((float) $propiedad->latitud, 7, '.', '') : 'No registrada' }}</span>
                    <span class="coord">Lng: {{ $propiedad->longitud !== null ? number_format((float) $propiedad->longitud, 7, '.', '') : 'No registrada' }}</span>
                </div>
                @if ($propiedad->latitud === null || $propiedad->longitud === null)
                    <p class="warn">Esta propiedad no tiene coordenadas GPS registradas.</p>
                @endif
            </aside>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        (() => {
            const mapElement = document.getElementById('mapa-detalle');
            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            const lat = Number.parseFloat(mapElement.dataset.lat || '');
            const lng = Number.parseFloat(mapElement.dataset.lng || '');
            const hasCoords = Number.isFinite(lat) && Number.isFinite(lng);

            const peruCenter = [-9.1900, -75.0152];
            const map = L.map(mapElement).setView(hasCoords ? [lat, lng] : peruCenter, hasCoords ? 16 : 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            if (hasCoords) {
                // Marcador unico que representa la ubicacion registrada de la propiedad.
                L.marker([lat, lng]).addTo(map);
            }
        })();

        (() => {
            const favButton = document.getElementById('detalle-fav-btn');
            const favChip = document.getElementById('detalle-favoritos-chip');
            const feedback = document.getElementById('detalle-fav-feedback');
            if (!favButton || !favChip) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const setMessage = (text, isError = false) => {
                if (!feedback) {
                    return;
                }

                feedback.textContent = text;
                feedback.style.color = isError ? '#9a3434' : '#355b4b';
            };

            favButton.addEventListener('click', async () => {
                const url = favButton.dataset.url || '';
                if (!url) {
                    setMessage('No se pudo identificar la propiedad.', true);
                    return;
                }

                favButton.disabled = true;
                setMessage('Actualizando favorito...');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json',
                        },
                    });

                    const payload = await response.json();
                    if (!response.ok || payload.ok === false) {
                        throw new Error(payload.message || 'No se pudo actualizar favorito.');
                    }

                    const favorita = payload.favorita === true;
                    favButton.dataset.favorita = favorita ? '1' : '0';
                    favButton.classList.toggle('active', favorita);
                    favButton.textContent = favorita ? '★' : '☆';
                    favButton.setAttribute('aria-label', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                    favButton.setAttribute('title', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                    favButton.setAttribute('aria-pressed', favorita ? 'true' : 'false');
                    favChip.textContent = `${payload.total_favoritos ?? 0} favorito(s)`;
                    setMessage(payload.message || 'Favorito actualizado.');
                } catch (error) {
                    setMessage(error?.message || 'No se pudo actualizar favorito.', true);
                } finally {
                    favButton.disabled = false;
                }
            });
        })();
    </script>
</body>
</html>
