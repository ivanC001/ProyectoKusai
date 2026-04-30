@extends('layouts.client')

@section('title', $propiedad->titulo . ' | Kusay.pe')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
@endsection

@section('content')
@php
    $galeriaImagenes = $propiedad->imagenes->isNotEmpty()
        ? $propiedad->imagenes
        : collect($propiedad->portadaImagen ? [$propiedad->portadaImagen] : []);
@endphp

<div class="wrap">
    <header class="top">
        <a class="crumb" href="{{ route('home') }}">Volver al inicio</a>
        <h1 class="title">{{ $propiedad->titulo }}</h1>
        <p class="subtitle">{{ $propiedad->direccion }}</p>
    </header>

    <div class="layout">
        <article class="panel">
            <section class="gallery" data-gallery>
                <div class="gallery-stage">
                    @forelse ($galeriaImagenes as $imagen)
                        <figure class="gallery-slide {{ $loop->first ? 'active' : '' }}" data-slide>
                            <img
                                src="{{ route('portal.propiedades.imagen', [$propiedad, $imagen]) }}"
                                alt="Foto {{ $loop->iteration }} de {{ $propiedad->titulo }}"
                            >
                        </figure>
                    @empty
                        <div class="gallery-empty">Sin fotos disponibles</div>
                    @endforelse

                    @if ($galeriaImagenes->count() > 1)
                        <button class="gallery-nav prev" type="button" data-gallery-prev aria-label="Foto anterior">&#10094;</button>
                        <button class="gallery-nav next" type="button" data-gallery-next aria-label="Foto siguiente">&#10095;</button>
                        <div class="gallery-counter">
                            <span data-gallery-current>1</span>/<span>{{ $galeriaImagenes->count() }}</span>
                        </div>
                    @endif
                </div>

                @if ($galeriaImagenes->count() > 1)
                    <div class="gallery-thumbs">
                        @foreach ($galeriaImagenes as $imagen)
                            <button
                                class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                                type="button"
                                data-gallery-thumb
                                data-index="{{ $loop->index }}"
                                aria-label="Ver foto {{ $loop->iteration }}"
                            >
                                <img
                                    src="{{ route('portal.propiedades.imagen', [$propiedad, $imagen]) }}"
                                    alt="Miniatura {{ $loop->iteration }}"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

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

                <dl class="detail-grid">
                    <div class="detail-item">
                        <dt>Area</dt>
                        <dd>{{ $propiedad->area !== null ? number_format((float) $propiedad->area, 2, '.', ',').' m2' : 'No especificada' }}</dd>
                    </div>
                    <div class="detail-item">
                        <dt>Dormitorios</dt>
                        <dd>{{ $propiedad->habitaciones !== null ? $propiedad->habitaciones : 'No especificado' }}</dd>
                    </div>
                    <div class="detail-item">
                        <dt>Banos</dt>
                        <dd>{{ $propiedad->banos !== null ? $propiedad->banos : 'No especificado' }}</dd>
                    </div>
                    <div class="detail-item">
                        <dt>Publicado</dt>
                        <dd>{{ optional($propiedad->created_at)->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>

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
                            {!! $esFavorita ? '&#9829;' : '&#9825;' !!}
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
            L.marker([lat, lng]).addTo(map);
        }
    })();

    (() => {
        const gallery = document.querySelector('[data-gallery]');
        if (!gallery) {
            return;
        }

        const slides = Array.from(gallery.querySelectorAll('[data-slide]'));
        const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
        const prevButton = gallery.querySelector('[data-gallery-prev]');
        const nextButton = gallery.querySelector('[data-gallery-next]');
        const currentCounter = gallery.querySelector('[data-gallery-current]');

        if (slides.length < 2) {
            return;
        }

        let index = 0;
        let autoTimer = null;

        const paint = (nextIndex) => {
            index = (nextIndex + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('active', slideIndex === index);
            });
            thumbs.forEach((thumb, thumbIndex) => {
                thumb.classList.toggle('active', thumbIndex === index);
            });

            if (currentCounter) {
                currentCounter.textContent = String(index + 1);
            }
        };

        const restartAutoplay = () => {
            if (autoTimer !== null) {
                clearInterval(autoTimer);
            }
            autoTimer = setInterval(() => {
                paint(index + 1);
            }, 5000);
        };

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                paint(index - 1);
                restartAutoplay();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                paint(index + 1);
                restartAutoplay();
            });
        }

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                const thumbIndex = Number.parseInt(thumb.dataset.index || '', 10);
                if (!Number.isInteger(thumbIndex)) {
                    return;
                }
                paint(thumbIndex);
                restartAutoplay();
            });
        });

        gallery.addEventListener('mouseenter', () => {
            if (autoTimer !== null) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        });
        gallery.addEventListener('mouseleave', restartAutoplay);

        paint(0);
        restartAutoplay();
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
                favButton.innerHTML = favorita ? '&#9829;' : '&#9825;';
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

