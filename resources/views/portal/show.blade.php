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

    $nombreAnunciante = trim(($propiedad->usuario?->name ?? '').' '.($propiedad->usuario?->apellidos ?? '')) ?: 'Anunciante';
    $anuncianteVerificado = $propiedad->usuario?->estaVerificadoPorKusay() ?? false;
    $nombreComprador = auth()->check() ? trim(auth()->user()->name.' '.auth()->user()->apellidos) : '';
    $telefonoComprador = auth()->user()?->telefono ?? '';
    $comentariosPublicos = $propiedad->comentarios->values();
    $comentariosConPuntaje = $comentariosPublicos->whereNotNull('puntaje');
    $promedioComentarios = $comentariosConPuntaje->isNotEmpty()
        ? number_format((float) $comentariosConPuntaje->avg('puntaje'), 1, '.', ',')
        : null;
@endphp

<div class="wrap">
    <header class="top">
        <div class="top-head">
            <h1 class="title">{{ $propiedad->titulo }}</h1>
            <a class="back-btn" href="{{ route('home') }}">
                <i class="bi bi-arrow-left-circle" aria-hidden="true"></i>
                <span>Volver al inicio</span>
            </a>
        </div>
        <p class="subtitle">
            {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
            {{ $propiedad->ubicacion?->provincia ?? 'Sin provincia' }},
            {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
        </p>
        @if ($propiedad->estaVerificadaPorKusay())
            <p class="verified-pill">
                <i class="bi bi-patch-check-fill" aria-hidden="true"></i>
                <span>Verificado por Kusay</span>
            </p>
        @endif
    </header>

    <div class="layout">
        <article class="panel">
            <section class="gallery" aria-label="Galeria de fotos de la propiedad">
                @if ($galeriaImagenes->isNotEmpty())
                    <div class="gallery-stage" id="detalle-gallery-stage">
                        @foreach ($galeriaImagenes as $imagen)
                            <figure class="gallery-slide {{ $loop->first ? 'active' : '' }}" data-gallery-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                                <img
                                    src="{{ route('portal.propiedades.imagen', [$propiedad, $imagen]) }}"
                                    alt="Foto {{ $loop->iteration }} de {{ $propiedad->titulo }}"
                                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                >
                            </figure>
                        @endforeach

                        @if ($galeriaImagenes->count() > 1)
                            <button type="button" class="gallery-nav prev" id="gallery-prev" aria-label="Foto anterior">&#10094;</button>
                            <button type="button" class="gallery-nav next" id="gallery-next" aria-label="Siguiente foto">&#10095;</button>
                            <div class="gallery-counter" id="gallery-counter">1 / {{ $galeriaImagenes->count() }}</div>
                        @endif
                    </div>

                    @if ($galeriaImagenes->count() > 1)
                        <div class="gallery-thumbs" id="gallery-thumbs">
                            @foreach ($galeriaImagenes as $imagen)
                                <button
                                    type="button"
                                    class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                                    data-gallery-thumb
                                    data-index="{{ $loop->index }}"
                                    aria-label="Ver foto {{ $loop->iteration }}"
                                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                >
                                    <img
                                        src="{{ route('portal.propiedades.imagen', [$propiedad, $imagen]) }}"
                                        alt="Miniatura {{ $loop->iteration }} de {{ $propiedad->titulo }}"
                                        loading="lazy"
                                    >
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="gallery-stage">
                        <div class="gallery-empty">Sin fotos disponibles</div>
                    </div>
                @endif
            </section>
            

            <div class="content">
                <div class="content-head">
                    <p class="price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                    @if ($propiedad->precio_usd !== null)
                        <p class="price-usd">US$ {{ number_format((float) $propiedad->precio_usd, 2, '.', ',') }}</p>
                    @endif
                    
                    <p class="meta">
                        {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                        {{ $propiedad->ubicacion?->provincia ?? 'Sin provincia' }},
                        {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                    </p>
                </div>

                <div class="detail-item detail-item-description">
                            <dt>Descripcion</dt>
                            <strong class="desc">{{ $propiedad->descripcion }}</strong>
                </div>
                

                <div class="address-row">
                    <div class="address-card">
                        <span>Direccion</span>
                        <strong>{{ $propiedad->direccion ?: 'No especificada' }}</strong>
                    </div>

                    <div class="address-card">
                        <span>Referencia</span>
                        <strong>{{ $propiedad->referencia ?: 'No especificada' }}</strong>
                    </div>
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
                </dl>

                <section class="comments-box">
                    <div class="comments-head">
                        <h2>Comentarios de usuarios</h2>
                        @if ($promedioComentarios !== null)
                            <span>{{ $promedioComentarios }} / 5 · {{ $comentariosPublicos->count() }} comentario(s)</span>
                        @else
                            <span>{{ $comentariosPublicos->count() }} comentario(s)</span>
                        @endif
                    </div>

                    @if (session('comentario_success'))
                        <p class="comments-alert success">{{ session('comentario_success') }}</p>
                    @endif

                    @if ($errors->comentario->any())
                        <p class="comments-alert error">Revisa tu comentario antes de publicar.</p>
                    @endif

                    @auth
                        <form class="comments-form" method="POST" action="{{ route('portal.propiedades.comentarios.store', $propiedad) }}">
                            @csrf
                            <label>Puntuacion</label>
                            <div class="rating-stars" role="radiogroup" aria-label="Puntuacion de la propiedad">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input
                                        type="radio"
                                        id="puntaje_{{ $i }}"
                                        name="puntaje"
                                        value="{{ $i }}"
                                        @checked((string) old('puntaje', '5') === (string) $i)
                                        required
                                    >
                                    <label for="puntaje_{{ $i }}" title="{{ $i }} estrella(s)">&#9733;</label>
                                @endfor
                            </div>
                            @error('puntaje', 'comentario') <span class="error-text">{{ $message }}</span> @enderror

                            <label for="comentario_mensaje">Escribe tu comentario</label>
                            <textarea
                                id="comentario_mensaje"
                                name="mensaje"
                                placeholder="Comparte tu opinion o consulta sobre esta propiedad..."
                                required
                            >{{ old('mensaje') }}</textarea>
                            @error('mensaje', 'comentario') <span class="error-text">{{ $message }}</span> @enderror
                            <button type="submit">Publicar comentario</button>
                        </form>
                    @else
                        <p class="comments-login">
                            <a href="{{ route('login') }}">Inicia sesion</a> para dejar un comentario en esta propiedad.
                        </p>
                    @endauth

                    <div class="comments-list">
                        @forelse ($comentariosPublicos as $comentario)
                            <article class="comment-item">
                                <div class="comment-avatar">{{ strtoupper(substr(trim(($comentario->usuario?->name ?? 'U')), 0, 1)) }}</div>
                                <div class="comment-body">
                                    <p class="comment-user">
                                        {{ trim(($comentario->usuario?->name ?? 'Usuario').' '.($comentario->usuario?->apellidos ?? '')) }}
                                    </p>
                                    <p class="review-score" aria-label="Puntaje {{ (int) ($comentario->puntaje ?? 0) }} de 5">
                                        {{ str_repeat('★', (int) ($comentario->puntaje ?? 0)) }}{{ str_repeat('☆', 5 - (int) ($comentario->puntaje ?? 0)) }}
                                    </p>
                                    <p class="comment-date">{{ optional($comentario->created_at)->format('Y-m-d H:i') }}</p>
                                    <p class="comment-text">{{ $comentario->mensaje }}</p>
                                </div>
                            </article>
                        @empty
                            <p class="comments-empty">Aun no hay comentarios para esta propiedad.</p>
                        @endforelse
                    </div>
                    <p class="comments-published">
                        Publicado: {{ optional($propiedad->created_at)->format('Y-m-d H:i') }}
                    </p>
                </section>

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

        <aside class="side-stack">
            <section class="side-panel contact-panel">
                <h2 class="side-title">Solicitar contacto</h2>
                <div class="contact-person">
                    <span class="contact-avatar">
                        {{ strtoupper(substr($nombreAnunciante, 0, 1)) }}
                    </span>
                    <div>
                        <p class="contact-name">{{ $nombreAnunciante }}</p>
                        @if ($anuncianteVerificado)
                            <p class="contact-verify is-verified">
                                <i class="bi bi-patch-check-fill" aria-hidden="true"></i>
                                <span>Usuario verificado por Kusay</span>
                            </p>
                        @endif
                        <p class="contact-note">El anunciante recibira tu solicitud y tus datos de contacto.</p>
                    </div>
                </div>

                @if (session('contacto_success'))
                    <p class="contact-alert success">{{ session('contacto_success') }}</p>
                @endif
                @if (session('contacto_info'))
                    <p class="contact-alert info">{{ session('contacto_info') }}</p>
                @endif

                @if ($errors->contacto->any())
                    <p class="contact-alert error">Revisa los datos de la solicitud.</p>
                @endif

                @auth
                    <form class="contact-form" method="POST" action="{{ route('portal.propiedades.contacto', $propiedad) }}">
                        @csrf
                        <div class="contact-field">
                            <label for="contacto_nombre">Nombre</label>
                            <input
                                id="contacto_nombre"
                                name="nombre"
                                type="text"
                                value="{{ old('nombre', $nombreComprador) }}"
                                placeholder="Tu nombre completo"
                                required
                            >
                            @error('nombre', 'contacto') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="contact-field">
                            <label for="contacto_email">Correo</label>
                            <input
                                id="contacto_email"
                                name="email"
                                type="email"
                                value="{{ old('email', auth()->user()?->email) }}"
                                placeholder="tu@email.com"
                                required
                            >
                            @error('email', 'contacto') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="contact-field">
                            <label for="contacto_telefono">Telefono</label>
                            <input
                                id="contacto_telefono"
                                name="telefono"
                                type="text"
                                value="{{ old('telefono', $telefonoComprador) }}"
                                placeholder="Numero para que te contacten"
                            >
                            @error('telefono', 'contacto') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <div class="contact-field">
                            <label for="contacto_mensaje">Mensaje</label>
                            <textarea
                                id="contacto_mensaje"
                                name="mensaje"
                                placeholder="Hola, estoy interesado en esta propiedad. Me gustaria recibir mas informacion."
                                required
                            >{{ old('mensaje', 'Hola, estoy interesado en esta propiedad. Me gustaria recibir mas informacion.') }}</textarea>
                            @error('mensaje', 'contacto') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <button class="contact-submit" type="submit">Enviar solicitud</button>
                    </form>
                @else
                    <p class="comments-login">
                        <a href="{{ route('login') }}">Inicia sesion</a> para enviar una solicitud de contacto.
                    </p>
                @endauth
            </section>

            <section class="side-panel map-panel">
                <h2 class="side-title">Ubicacion en mapa</h2>
                <p class="map-subtitle">Marcador segun la ubicacion registrada.</p>
                <div id="mapa-detalle" data-lat="{{ $propiedad->latitud }}" data-lng="{{ $propiedad->longitud }}"></div>
                <div class="coords">
                    <span class="coord">Lat: {{ $propiedad->latitud !== null ? number_format((float) $propiedad->latitud, 7, '.', '') : 'No registrada' }}</span>
                    <span class="coord">Lng: {{ $propiedad->longitud !== null ? number_format((float) $propiedad->longitud, 7, '.', '') : 'No registrada' }}</span>
                </div>
                @if ($propiedad->latitud === null || $propiedad->longitud === null)
                    <p class="warn">Esta propiedad no tiene coordenadas GPS registradas.</p>
                @endif
            </section>
        </aside>
    </div>

    <div class="footer-actions">
        <a class="back-btn back-btn-end" href="{{ route('home') }}">
            <i class="bi bi-house-door" aria-hidden="true"></i>
            <span>Regresar al inicio</span>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    (() => {
        const slides = Array.from(document.querySelectorAll('[data-gallery-slide]'));
        if (slides.length <= 1) {
            return;
        }

        const thumbs = Array.from(document.querySelectorAll('[data-gallery-thumb]'));
        const prevButton = document.getElementById('gallery-prev');
        const nextButton = document.getElementById('gallery-next');
        const counter = document.getElementById('gallery-counter');
        let currentIndex = 0;

        const render = () => {
            slides.forEach((slide, index) => {
                const isActive = index === currentIndex;
                slide.classList.toggle('active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            thumbs.forEach((thumb, index) => {
                const isActive = index === currentIndex;
                thumb.classList.toggle('active', isActive);
                thumb.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            if (counter) {
                counter.textContent = `${currentIndex + 1} / ${slides.length}`;
            }
        };

        const goTo = (index) => {
            if (index < 0) {
                currentIndex = slides.length - 1;
            } else if (index >= slides.length) {
                currentIndex = 0;
            } else {
                currentIndex = index;
            }

            render();
        };

        prevButton?.addEventListener('click', () => goTo(currentIndex - 1));
        nextButton?.addEventListener('click', () => goTo(currentIndex + 1));

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                const index = Number.parseInt(thumb.dataset.index || '0', 10);
                if (Number.isNaN(index)) {
                    return;
                }

                goTo(index);
            });
        });

        render();
    })();

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
