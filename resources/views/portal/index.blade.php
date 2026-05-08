@extends('layouts.client')

@section('title', !empty($filtros['solo_favoritos']) ? 'Propiedades favoritas | Kusay.pe' : 'Kusay.pe | Portal inmobiliario')

@section('content')
    <!-- Estilos de esta vista: resources/css/pages/portal-index.css -->
@php
        $tiposProyecto = $tiposPropiedad->filter(function ($tipo) {
            return str_contains(mb_strtolower($tipo->nombre), 'proyecto');
        });
        $tipoProyecto = $tiposProyecto->first();

        $queryBase = request()->except(['page', 'modo', 'operacion']);
        $soloFavoritos = !empty($filtros['solo_favoritos']);
        $modoFiltro = $filtros['modo'] ?? '';
        $modoActivo = $modoFiltro !== '' ? $modoFiltro : ($soloFavoritos ? '' : 'comprar');
        $tituloListado = $soloFavoritos
            ? 'Propiedades favoritas'
            : match ($modoActivo) {
                'alquilar' => 'Propiedades en alquiler',
                'proyectos' => 'Proyectos inmobiliarios',
                'comprar' => 'Propiedades en venta',
                default => 'Todas las propiedades',
            };
        $subtituloListado = $soloFavoritos
            ? 'Lista de propiedades que guardaste para revisar luego.'
            : match ($modoActivo) {
                'alquilar' => 'Resultados filtrados para alquiler segun tus criterios.',
                'proyectos' => 'Proyectos activos disponibles segun ubicacion y filtros.',
                'comprar' => 'Resultados filtrados para compra segun ubicacion y presupuesto.',
                default => 'Catalogo principal organizado por tipo, ubicacion y filtros reales.',
            };
        $tiposBusqueda = $modoFiltro === 'proyectos' ? $tiposProyecto : $tiposPropiedad;
    @endphp

    <!-- Hero principal -->
    <section class="hero" id="inicio">
            <div class="hero-inner">
                <div class="hero-tag">Portal inmobiliario N1 - Selva y Sierra peruana</div>
                <h1>Tu proxima propiedad en la <em>Selva</em> y <em>Sierra</em> del Peru</h1>
                <p class="hero-sub">Terrenos, casas, departamentos, lotes, chacras y mas. Publica gratis y conecta directo con compradores.</p>

                <div class="search-box">
                    <div class="search-tabs">
                        <a
                            href="{{ route('home', array_merge($queryBase, ['modo' => 'comprar', 'tipo_propiedad_id' => null])) }}#props"
                            class="{{ $modoActivo === 'comprar' ? 'active' : '' }}"
                        >
                            Comprar
                        </a>
                        <a
                            href="{{ route('home', array_merge($queryBase, ['modo' => 'alquilar', 'tipo_propiedad_id' => null])) }}#props"
                            class="{{ $modoActivo === 'alquilar' ? 'active' : '' }}"
                        >
                            Alquilar
                        </a>
                        <a
                            href="{{ route('home', array_merge($queryBase, ['modo' => 'proyectos', 'tipo_propiedad_id' => null])) }}#props"
                            class="{{ $modoActivo === 'proyectos' ? 'active' : '' }}"
                        >
                            Proyectos
                        </a>
                    </div>
                    <div class="search-body">
                        <form class="search-form" method="GET" action="{{ route('home') }}#props">
                            <input type="hidden" name="modo" value="{{ $modoFiltro }}">
                            <input type="hidden" name="ciudad" value="{{ $filtros['ciudad'] }}">
                            <input type="hidden" name="orden" value="{{ $filtros['orden'] }}">
                            @if (!empty($filtros['solo_favoritos']))
                                <input type="hidden" name="favoritos" value="1">
                            @endif

                            <div class="row">
                                <input
                                    type="text"
                                    name="ubicacion"
                                    value="{{ $filtros['ubicacion'] }}"
                                    placeholder="Ciudad, zona o referencia..."
                                >
                                <select name="tipo_propiedad_id">
                                    <option value="">{{ $modoFiltro === 'proyectos' ? 'Todo proyecto' : 'Todo tipo' }}</option>
                                    @foreach ($tiposBusqueda as $tipo)
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

        @if (! $soloFavoritos && $destacadas->isNotEmpty())
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
                            @if ($propiedad->precio_usd !== null)
                                <p class="card-price-usd">US$ {{ number_format((float) $propiedad->precio_usd, 2, '.', ',') }}</p>
                            @endif
                            <div class="meta-row">
                                <span class="meta-chip">{{ $propiedad->visitas_count }} clic(s)</span>
                                <span class="meta-chip">{{ $propiedad->comentarios_count }} comentario(s)</span>
                                <span class="meta-chip" data-favoritos-id="{{ $propiedad->id }}">{{ $propiedad->favoritos_count }} favorito(s)</span>
                            </div>
                            <div class="card-actions">
                                @auth
                                    <button
                                        type="button"
                                        class="card-favorite-btn {{ $favoritasIds->contains($propiedad->id) ? 'active' : '' }}"
                                        data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                        data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                        data-propiedad-id="{{ $propiedad->id }}"
                                        aria-label="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                        aria-pressed="{{ $favoritasIds->contains($propiedad->id) ? 'true' : 'false' }}"
                                        title="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                    >
                                        <i class="card-favorite-glyph bi {{ $favoritasIds->contains($propiedad->id) ? 'bi-heart-fill' : 'bi-heart' }}" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="card-favorite-btn" title="Inicia sesion para guardar favoritos">
                                        <i class="card-favorite-glyph bi bi-heart" aria-hidden="true"></i>
                                    </a>
                                @endauth
                                <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">
                                    <i class="bi bi-arrow-up-right-circle" aria-hidden="true"></i>
                                    <span>Ver detalle</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-list">Aun no hay propiedades destacadas.</div>
                @endforelse
            </div>
        </section>
        @endif

        <section class="sec" id="props">
            <div class="shead">
                <div>
                    <p class="eyebrow">Disponibles ahora</p>
                    <h2 class="stitle">{{ $tituloListado }}</h2>
                    <p class="ssub">{{ $subtituloListado }}</p>
                </div>
                <div>
                    <a href="{{ route('propiedades.create') }}" class="btn btn-main">+ Publicar gratis</a>
                    <a href="{{ route('home') }}#props" class="btn btn-outline">Limpiar filtros</a>
                </div>
            </div>

            @if ($propiedades->isEmpty())
                @if ($soloFavoritos)
                    <div class="empty-list">
                        Aun no marcaste propiedades favoritas.
                        <a href="{{ route('home') }}#props">Ver todas las propiedades</a>.
                    </div>
                @else
                    <div class="empty-list">No encontramos propiedades con esos filtros. Prueba con otros criterios.</div>
                @endif
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
                                @if ($propiedad->precio_usd !== null)
                                    <p class="card-price-usd">US$ {{ number_format((float) $propiedad->precio_usd, 2, '.', ',') }}</p>
                                @endif
                                <div class="meta-row">
                                    <span class="meta-chip">{{ ucfirst($propiedad->tipo) }}</span>
                                    <span class="meta-chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                                    <span class="meta-chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                                    <span class="meta-chip">{{ $propiedad->visitas_count }} clic(s)</span>
                                    <span class="meta-chip">{{ $propiedad->comentarios_count }} comentario(s)</span>
                                    <span class="meta-chip" data-favoritos-id="{{ $propiedad->id }}">{{ $propiedad->favoritos_count }} favorito(s)</span>
                                </div>
                                <div class="card-actions">
                                    @auth
                                        <button
                                            type="button"
                                            class="card-favorite-btn {{ $favoritasIds->contains($propiedad->id) ? 'active' : '' }}"
                                            data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                            data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                            data-propiedad-id="{{ $propiedad->id }}"
                                            aria-label="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                            aria-pressed="{{ $favoritasIds->contains($propiedad->id) ? 'true' : 'false' }}"
                                            title="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                        >
                                            <i class="card-favorite-glyph bi {{ $favoritasIds->contains($propiedad->id) ? 'bi-heart-fill' : 'bi-heart' }}" aria-hidden="true"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="card-favorite-btn" title="Inicia sesion para guardar favoritos">
                                            <i class="card-favorite-glyph bi bi-heart" aria-hidden="true"></i>
                                        </a>
                                    @endauth
                                    <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">
                                    <i class="bi bi-arrow-up-right-circle" aria-hidden="true"></i>
                                    <span>Ver detalle</span>
                                </a>
                                </div>
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

        @if (!empty($mostrarBloquesPrincipal))
            <section class="sec split-sec" id="explorar-modos">

                <section class="split-block" id="bloque-alquiler">
                    <div class="split-block-head">
                        <h3 class="split-title">Propiedades en alquiler</h3>
                        <p class="split-sub">Solo publicaciones de alquiler disponibles.</p>
                    </div>
                    @if ($bloquesPrincipal['alquiler']->isEmpty())
                        <div class="empty-list">Aun no hay propiedades en alquiler disponibles.</div>
                    @else
                        <div class="prop-grid">
                            @foreach ($bloquesPrincipal['alquiler'] as $propiedad)
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
                                        @if ($propiedad->precio_usd !== null)
                                            <p class="card-price-usd">US$ {{ number_format((float) $propiedad->precio_usd, 2, '.', ',') }}</p>
                                        @endif
                                        <div class="meta-row">
                                            <span class="meta-chip">Alquiler</span>
                                            <span class="meta-chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                                            <span class="meta-chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                                            <span class="meta-chip">{{ $propiedad->visitas_count }} clic(s)</span>
                                            <span class="meta-chip">{{ $propiedad->comentarios_count }} comentario(s)</span>
                                            <span class="meta-chip" data-favoritos-id="{{ $propiedad->id }}">{{ $propiedad->favoritos_count }} favorito(s)</span>
                                        </div>
                                        <div class="card-actions">
                                            @auth
                                                <button
                                                    type="button"
                                                    class="card-favorite-btn {{ $favoritasIds->contains($propiedad->id) ? 'active' : '' }}"
                                                    data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                                    data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                                    data-propiedad-id="{{ $propiedad->id }}"
                                                    aria-label="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                                    aria-pressed="{{ $favoritasIds->contains($propiedad->id) ? 'true' : 'false' }}"
                                                    title="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                                >
                                                    <i class="card-favorite-glyph bi {{ $favoritasIds->contains($propiedad->id) ? 'bi-heart-fill' : 'bi-heart' }}" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}" class="card-favorite-btn" title="Inicia sesion para guardar favoritos">
                                                    <i class="card-favorite-glyph bi bi-heart" aria-hidden="true"></i>
                                                </a>
                                            @endauth
                                            <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">
                                    <i class="bi bi-arrow-up-right-circle" aria-hidden="true"></i>
                                    <span>Ver detalle</span>
                                </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="split-block" id="bloque-proyectos">
                    <div class="split-block-head">
                        <h3 class="split-title">Proyectos inmobiliarios</h3>
                        <p class="split-sub">Solo publicaciones de tipo proyecto inmobiliario.</p>
                    </div>
                    @if ($bloquesPrincipal['proyectos']->isEmpty())
                        <div class="empty-list">Aun no hay proyectos inmobiliarios disponibles.</div>
                    @else
                        <div class="prop-grid">
                            @foreach ($bloquesPrincipal['proyectos'] as $propiedad)
                                <article class="card">
                                    @if ($propiedad->portadaImagen)
                                        <img class="card-cover" src="{{ route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen]) }}" alt="Portada de {{ $propiedad->titulo }}">
                                    @else
                                        <div class="card-empty">Sin foto</div>
                                    @endif
                                    <div class="card-body">
                                        <p class="card-type">{{ $propiedad->tipoPropiedad?->nombre ?? 'Proyecto' }}</p>
                                        <h3 class="card-title">{{ $propiedad->titulo }}</h3>
                                        <p class="card-loc">
                                            {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                                            {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                                        </p>
                                        <p class="card-price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                                        @if ($propiedad->precio_usd !== null)
                                            <p class="card-price-usd">US$ {{ number_format((float) $propiedad->precio_usd, 2, '.', ',') }}</p>
                                        @endif
                                        <div class="meta-row">
                                            <span class="meta-chip">Proyecto</span>
                                            <span class="meta-chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                                            <span class="meta-chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                                            <span class="meta-chip">{{ $propiedad->visitas_count }} clic(s)</span>
                                            <span class="meta-chip">{{ $propiedad->comentarios_count }} comentario(s)</span>
                                            <span class="meta-chip" data-favoritos-id="{{ $propiedad->id }}">{{ $propiedad->favoritos_count }} favorito(s)</span>
                                        </div>
                                        <div class="card-actions">
                                            @auth
                                                <button
                                                    type="button"
                                                    class="card-favorite-btn {{ $favoritasIds->contains($propiedad->id) ? 'active' : '' }}"
                                                    data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                                    data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                                    data-propiedad-id="{{ $propiedad->id }}"
                                                    aria-label="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                                    aria-pressed="{{ $favoritasIds->contains($propiedad->id) ? 'true' : 'false' }}"
                                                    title="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                                >
                                                    <i class="card-favorite-glyph bi {{ $favoritasIds->contains($propiedad->id) ? 'bi-heart-fill' : 'bi-heart' }}" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}" class="card-favorite-btn" title="Inicia sesion para guardar favoritos">
                                                    <i class="card-favorite-glyph bi bi-heart" aria-hidden="true"></i>
                                                </a>
                                            @endauth
                                            <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">
                                    <i class="bi bi-arrow-up-right-circle" aria-hidden="true"></i>
                                    <span>Ver detalle</span>
                                </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </section>
        @endif

@endsection

@section('scripts')
<script>
        // Favoritos con actualizacion en vivo de estado y contador.
        (() => {
            const favoriteButtons = Array.from(document.querySelectorAll('.card-favorite-btn[data-favorito-url]'));
            if (!favoriteButtons.length) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const loginUrl = @json(route('login'));

            favoriteButtons.forEach((button) => {
                button.addEventListener('click', async () => {
                    const favoritoUrl = button.dataset.favoritoUrl || '';
                    if (!favoritoUrl) {
                        return;
                    }

                    button.disabled = true;

                    try {
                        const response = await fetch(favoritoUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                Accept: 'application/json',
                            },
                        });

                        if (response.status === 401) {
                            window.location.href = loginUrl;
                            return;
                        }

                        const payload = await response.json();
                        if (!response.ok || payload.ok === false) {
                            throw new Error(payload.message || 'No se pudo actualizar favorito.');
                        }

                        const favorita = payload.favorita === true;
                        button.dataset.favorita = favorita ? '1' : '0';
                        button.classList.toggle('active', favorita);
                        const glyph = button.querySelector('.card-favorite-glyph');
                        if (glyph) {
                            glyph.classList.remove('bi-heart', 'bi-heart-fill');
                            glyph.classList.add(favorita ? 'bi-heart-fill' : 'bi-heart');
                        }
                        button.setAttribute('aria-label', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                        button.setAttribute('title', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                        button.setAttribute('aria-pressed', favorita ? 'true' : 'false');

                        const propiedadId = button.dataset.propiedadId || '';
                        if (propiedadId !== '') {
                            document.querySelectorAll(`.meta-chip[data-favoritos-id="${propiedadId}"]`).forEach((counter) => {
                                counter.textContent = `${payload.total_favoritos ?? 0} favorito(s)`;
                            });
                        }
                    } catch (error) {
                        console.error(error);
                    } finally {
                        button.disabled = false;
                    }
                });
            });
        })();
    </script>
@endsection



