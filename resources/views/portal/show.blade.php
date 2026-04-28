@extends('layouts.client')

@section('title', $propiedad->titulo . ' | Kusay.pe')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
@endsection

@section('content')
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
                            {!! $esFavorita ? '&#9733;' : '&#9734;' !!}
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
@endsection

@section('scripts')
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
                favButton.textContent = favorita ? '\u2605' : '\u2606';
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
@endsection
