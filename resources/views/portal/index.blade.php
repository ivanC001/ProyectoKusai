@extends('layouts.client')

@section('title', 'Kusay.pe | Portal inmobiliario')

@section('content')
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

<section class="quick-nav">
        <details class="quick-block quick-block-cats" data-quick-block data-quick-block-cats open>
            <summary class="quick-block-summary" aria-label="Ver categorias">
                <span class="quick-block-title">Categorias</span>
                <span class="quick-block-caret">&#9662;</span>
            </summary>
            <div class="quick-block-body">
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
            </div>
        </details>

        <details class="quick-block quick-block-filters" data-quick-block data-quick-block-filters open>
            <summary class="quick-block-summary" aria-label="Ver filtros">
                <span class="quick-block-title">Ubicacion y orden</span>
                <span class="quick-block-caret">&#9662;</span>
            </summary>
            <div class="quick-block-body">
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
            </div>
        </details>
    </section>

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
                            <div class="meta-row">
                                <span class="meta-chip">{{ $propiedad->visitas_count }} clic(s)</span>
                                <span class="meta-chip">{{ $propiedad->favoritos_count }} favorito(s)</span>
                            </div>
                            @php
                                $descripcionModal = \Illuminate\Support\Str::limit(
                                    trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $propiedad->descripcion))),
                                    220
                                );
                                $ubicacionModal = trim(($propiedad->ubicacion?->distrito ?? 'Sin distrito').', '.($propiedad->ubicacion?->departamento ?? 'Sin departamento'));
                                $imagenModal = $propiedad->portadaImagen
                                    ? route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen])
                                    : '';
                            @endphp
                            <a
                                class="card-link js-open-detail"
                                href="#"
                                data-title="{{ $propiedad->titulo }}"
                                data-tipo="{{ $propiedad->tipoPropiedad?->nombre ?? 'Propiedad' }}"
                                data-ubicacion="{{ $ubicacionModal }}"
                                data-precio="{{ number_format((float) $propiedad->precio, 2, '.', ',') }}"
                                data-descripcion="{{ $descripcionModal }}"
                                data-operacion="{{ ucfirst($propiedad->tipo) }}"
                                data-fotos="{{ $propiedad->imagenes_count }}"
                                data-contactos="{{ $propiedad->contactos_count }}"
                                data-dormitorios="{{ $propiedad->habitaciones ?? '' }}"
                                data-area="{{ $propiedad->area !== null ? number_format((float) $propiedad->area, 2, '.', ',').' m2' : '' }}"
                                data-image="{{ $imagenModal }}"
                                data-lat="{{ $propiedad->latitud ?? '' }}"
                                data-lng="{{ $propiedad->longitud ?? '' }}"
                                data-clics="{{ $propiedad->visitas_count }}"
                                data-favoritos="{{ $propiedad->favoritos_count }}"
                                data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                data-click-url="{{ route('portal.propiedades.click', $propiedad) }}"
                                data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                            >
                                Ver propiedad
                            </a>
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
                                    <span class="meta-chip">{{ $propiedad->visitas_count }} clic(s)</span>
                                    <span class="meta-chip">{{ $propiedad->favoritos_count }} favorito(s)</span>
                                </div>
                                @php
                                    $descripcionModal = \Illuminate\Support\Str::limit(
                                        trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $propiedad->descripcion))),
                                        220
                                    );
                                    $ubicacionModal = trim(($propiedad->ubicacion?->distrito ?? 'Sin distrito').', '.($propiedad->ubicacion?->departamento ?? 'Sin departamento'));
                                    $imagenModal = $propiedad->portadaImagen
                                        ? route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen])
                                        : '';
                                @endphp
                                <a
                                    class="card-link js-open-detail"
                                    href="#"
                                    data-title="{{ $propiedad->titulo }}"
                                    data-tipo="{{ $propiedad->tipoPropiedad?->nombre ?? 'Propiedad' }}"
                                    data-ubicacion="{{ $ubicacionModal }}"
                                    data-precio="{{ number_format((float) $propiedad->precio, 2, '.', ',') }}"
                                    data-descripcion="{{ $descripcionModal }}"
                                    data-operacion="{{ ucfirst($propiedad->tipo) }}"
                                    data-fotos="{{ $propiedad->imagenes_count }}"
                                    data-contactos="{{ $propiedad->contactos_count }}"
                                    data-dormitorios="{{ $propiedad->habitaciones ?? '' }}"
                                    data-area="{{ $propiedad->area !== null ? number_format((float) $propiedad->area, 2, '.', ',').' m2' : '' }}"
                                    data-image="{{ $imagenModal }}"
                                    data-lat="{{ $propiedad->latitud ?? '' }}"
                                    data-lng="{{ $propiedad->longitud ?? '' }}"
                                    data-clics="{{ $propiedad->visitas_count }}"
                                    data-favoritos="{{ $propiedad->favoritos_count }}"
                                    data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                    data-click-url="{{ route('portal.propiedades.click', $propiedad) }}"
                                    data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                >
                                    Ver detalle
                                </a>
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
        </section>`r`n<div class="prop-modal" id="prop-detail-modal" hidden>
        <div class="prop-modal-backdrop" data-modal-close></div>
        <article class="prop-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="prop-modal-title">
            <button class="prop-modal-close" type="button" aria-label="Cerrar detalle" data-modal-close>&times;</button>
            <div class="prop-modal-media">
                <img id="prop-modal-image" alt="Imagen de propiedad">
                <div id="prop-modal-image-empty" class="prop-modal-media-empty">Sin foto disponible</div>
                <div id="prop-modal-geo" class="prop-modal-geo" hidden>
                    <div class="prop-modal-geo-top">
                        <span class="prop-modal-geo-pin-wrap">
                            <span class="prop-modal-geo-pin">&#128205;</span>
                        </span>
                        <div class="prop-modal-geo-head">
                            <p class="prop-modal-geo-title">Ubicacion GPS marcada</p>
                            <p class="prop-modal-geo-text" id="prop-modal-geo-text">Lat: - | Lng: -</p>
                        </div>
                    </div>
                    <div class="prop-modal-geo-actions">
                        <a id="prop-modal-geo-map" class="prop-modal-geo-link" href="#" target="_blank" rel="noopener noreferrer">Ver en OpenStreetMap</a>
                        <button id="prop-modal-geo-copy" class="prop-modal-geo-copy" type="button">Copiar coordenadas</button>
                    </div>
                </div>
            </div>
            <div class="prop-modal-content">
                <p class="prop-modal-type" id="prop-modal-type">Propiedad</p>
                <h3 class="prop-modal-title" id="prop-modal-title">Detalle</h3>
                <p class="prop-modal-location" id="prop-modal-location">Ubicacion</p>
                <p class="prop-modal-price" id="prop-modal-price">S/ 0.00</p>
                <p class="prop-modal-desc" id="prop-modal-desc">Descripcion de la propiedad.</p>
                <div class="prop-modal-chips" id="prop-modal-chips"></div>
                <div class="prop-modal-actions">
                    @auth
                        <button class="prop-modal-btn fav" id="prop-modal-favorite" type="button" hidden aria-label="Agregar a favoritos" aria-pressed="false" title="Agregar a favoritos">?</button>
                    @endauth
                    <button class="prop-modal-btn main" type="button" data-modal-close>Entendido</button>
                    <button class="prop-modal-btn soft" type="button" data-modal-close>Cerrar</button>
                </div>
                <p class="prop-modal-feedback" id="prop-modal-feedback" hidden></p>
            </div>
        </article>
    </div>

    
@endsection

@section('scripts')
<script>
        (() => {
            const wrap = document.querySelector('[data-cat-wrap]');
            const list = document.querySelector('[data-cat-list]');
            const toggle = document.querySelector('[data-cat-toggle]');
            const catsBlock = document.querySelector('[data-quick-block-cats]');
            const filtersBlock = document.querySelector('[data-quick-block-filters]');
            const quickBlocks = Array.from(document.querySelectorAll('[data-quick-block]'));
            const mobileQuery = window.matchMedia('(max-width: 760px)');

            const setCollapsed = () => {
                if (!wrap || !toggle) {
                    return;
                }

                wrap.classList.remove('is-expanded');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.textContent = 'Ver mas categorias';
            };

            const isCatsVisible = () => {
                if (!mobileQuery.matches) {
                    return true;
                }

                if (!catsBlock) {
                    return true;
                }

                return catsBlock.open;
            };

            const updateToggleVisibility = () => {
                if (!wrap || !list || !toggle) {
                    return;
                }

                if (!mobileQuery.matches) {
                    wrap.classList.remove('has-hidden');
                    setCollapsed();
                    return;
                }

                if (!isCatsVisible()) {
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

            if (toggle && wrap) {
                toggle.addEventListener('click', () => {
                    const expand = !wrap.classList.contains('is-expanded');
                    wrap.classList.toggle('is-expanded', expand);
                    toggle.setAttribute('aria-expanded', expand ? 'true' : 'false');
                    toggle.textContent = expand ? 'Ver menos categorias' : 'Ver mas categorias';
                });
            }

            quickBlocks.forEach((block) => {
                block.addEventListener('toggle', () => {
                    if (mobileQuery.matches && block.open) {
                        quickBlocks.forEach((otherBlock) => {
                            if (otherBlock !== block) {
                                otherBlock.open = false;
                            }
                        });
                    }

                    if (block === catsBlock && !block.open) {
                        setCollapsed();
                    }

                    updateToggleVisibility();
                });
            });

            const syncDetailsByViewport = () => {
                if (!quickBlocks.length) {
                    return;
                }

                if (!mobileQuery.matches) {
                    quickBlocks.forEach((block) => {
                        block.open = true;
                    });
                    return;
                }

                if (catsBlock) {
                    catsBlock.open = false;
                }
                if (filtersBlock) {
                    filtersBlock.open = false;
                }
            };

            syncDetailsByViewport();
            window.addEventListener('resize', updateToggleVisibility);

            if (typeof mobileQuery.addEventListener === 'function') {
                mobileQuery.addEventListener('change', () => {
                    syncDetailsByViewport();
                    updateToggleVisibility();
                });
            } else if (typeof mobileQuery.addListener === 'function') {
                mobileQuery.addListener(() => {
                    syncDetailsByViewport();
                    updateToggleVisibility();
                });
            }

            updateToggleVisibility();
        })();

        (() => {
            const modal = document.getElementById('prop-detail-modal');
            const triggers = Array.from(document.querySelectorAll('.js-open-detail'));
            if (!modal || !triggers.length) {
                return;
            }

            const image = document.getElementById('prop-modal-image');
            const imageEmpty = document.getElementById('prop-modal-image-empty');
            const type = document.getElementById('prop-modal-type');
            const title = document.getElementById('prop-modal-title');
            const location = document.getElementById('prop-modal-location');
            const price = document.getElementById('prop-modal-price');
            const description = document.getElementById('prop-modal-desc');
            const chips = document.getElementById('prop-modal-chips');
            const geoBox = document.getElementById('prop-modal-geo');
            const geoText = document.getElementById('prop-modal-geo-text');
            const geoMapLink = document.getElementById('prop-modal-geo-map');
            const geoCopyButton = document.getElementById('prop-modal-geo-copy');
            const favoriteButton = document.getElementById('prop-modal-favorite');
            const feedback = document.getElementById('prop-modal-feedback');
            const closeButtons = Array.from(modal.querySelectorAll('[data-modal-close]'));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const loginUrl = @json(route('login'));
            let currentCoordsText = '';
            let activeTrigger = null;

            const closeModal = () => {
                modal.hidden = true;
                document.body.classList.remove('modal-open');
            };

            const showFeedback = (message, isError = false) => {
                if (!feedback) {
                    return;
                }

                if (!message) {
                    feedback.hidden = true;
                    feedback.textContent = '';
                    return;
                }

                feedback.hidden = false;
                feedback.textContent = message;
                feedback.style.color = isError ? '#9a3434' : '#315847';
            };

            const updateFavoriteButton = (trigger) => {
                if (!favoriteButton || !trigger) {
                    return;
                }

                const favorita = trigger.dataset.favorita === '1';
                const favoritoUrl = trigger.dataset.favoritoUrl || '';

                favoriteButton.hidden = favoritoUrl === '';
                favoriteButton.dataset.url = favoritoUrl;
                favoriteButton.classList.toggle('active', favorita);
                favoriteButton.textContent = favorita ? '?' : '?';
                favoriteButton.setAttribute('aria-label', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                favoriteButton.setAttribute('title', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                favoriteButton.setAttribute('aria-pressed', favorita ? 'true' : 'false');
            };

            const buildChips = (trigger) => {
                if (!chips) {
                    return;
                }

                chips.innerHTML = '';

                const chipValues = [
                    trigger.dataset.operacion ? `Operacion: ${trigger.dataset.operacion}` : '',
                    trigger.dataset.fotos ? `${trigger.dataset.fotos} foto(s)` : '',
                    trigger.dataset.contactos ? `${trigger.dataset.contactos} contacto(s)` : '',
                    trigger.dataset.clics ? `${trigger.dataset.clics} clic(s)` : '',
                    trigger.dataset.favoritos ? `${trigger.dataset.favoritos} favorito(s)` : '',
                    trigger.dataset.dormitorios ? `${trigger.dataset.dormitorios} dormitorio(s)` : '',
                    trigger.dataset.area ? `Area: ${trigger.dataset.area}` : '',
                ].filter(Boolean);

                chipValues.forEach((value) => {
                    const span = document.createElement('span');
                    span.className = 'prop-modal-chip';
                    span.textContent = value;
                    chips.appendChild(span);
                });
            };

            const openModal = (trigger) => {
                activeTrigger = trigger;
                const imageUrl = trigger.dataset.image || '';
                const latValue = Number.parseFloat(trigger.dataset.lat || '');
                const lngValue = Number.parseFloat(trigger.dataset.lng || '');
                const hasCoords = Number.isFinite(latValue) && Number.isFinite(lngValue);

                if (image && imageEmpty) {
                    if (imageUrl !== '') {
                        image.src = imageUrl;
                        image.style.display = 'block';
                        imageEmpty.style.display = 'none';
                    } else {
                        image.removeAttribute('src');
                        image.style.display = 'none';
                        imageEmpty.style.display = 'grid';
                    }
                }

                if (geoBox && geoText) {
                    if (hasCoords) {
                        currentCoordsText = `${latValue.toFixed(7)}, ${lngValue.toFixed(7)}`;
                        geoText.textContent = `Lat: ${latValue.toFixed(7)} | Lng: ${lngValue.toFixed(7)}`;
                        geoBox.classList.remove('no-coords');
                        geoBox.hidden = false;

                        if (geoMapLink) {
                            geoMapLink.href = `https://www.openstreetmap.org/?mlat=${latValue.toFixed(7)}&mlon=${lngValue.toFixed(7)}#map=17/${latValue.toFixed(7)}/${lngValue.toFixed(7)}`;
                            geoMapLink.style.pointerEvents = 'auto';
                            geoMapLink.style.opacity = '1';
                        }
                    } else {
                        currentCoordsText = '';
                        geoText.textContent = 'Coordenadas no registradas para esta propiedad.';
                        geoBox.classList.add('no-coords');
                        geoBox.hidden = false;

                        if (geoMapLink) {
                            geoMapLink.href = '#';
                            geoMapLink.style.pointerEvents = 'none';
                            geoMapLink.style.opacity = '.55';
                        }
                    }
                }

                if (type) {
                    type.textContent = trigger.dataset.tipo || 'Propiedad';
                }
                if (title) {
                    title.textContent = trigger.dataset.title || 'Detalle de propiedad';
                }
                if (location) {
                    location.textContent = trigger.dataset.ubicacion || 'Sin ubicacion';
                }
                if (price) {
                    price.textContent = `S/ ${trigger.dataset.precio || '0.00'}`;
                }
                if (description) {
                    description.textContent = trigger.dataset.descripcion || 'Sin descripcion disponible.';
                }

                buildChips(trigger);
                updateFavoriteButton(trigger);
                showFeedback('');

                modal.hidden = false;
                document.body.classList.add('modal-open');
            };

            const reportClick = (trigger) => {
                const clickUrl = trigger?.dataset?.clickUrl || '';
                if (!clickUrl || !csrfToken) {
                    return;
                }

                fetch(clickUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                    keepalive: true,
                }).catch(() => {
                    // El registro de clic es auxiliar; ignoramos errores de red.
                });
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', (event) => {
                    event.preventDefault();
                    reportClick(trigger);

                    const clicsActuales = Number.parseInt(trigger.dataset.clics || '', 10);
                    if (Number.isFinite(clicsActuales)) {
                        trigger.dataset.clics = String(clicsActuales + 1);
                    }

                    openModal(trigger);
                });
            });

            if (favoriteButton) {
                favoriteButton.addEventListener('click', async () => {
                    if (!activeTrigger) {
                        return;
                    }

                    const favoritoUrl = favoriteButton.dataset.url || '';
                    if (!favoritoUrl) {
                        showFeedback('No se pudo identificar la propiedad.', true);
                        return;
                    }

                    favoriteButton.disabled = true;
                    showFeedback('Guardando favorito...');

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

                        activeTrigger.dataset.favorita = payload.favorita ? '1' : '0';
                        activeTrigger.dataset.favoritos = String(payload.total_favoritos ?? 0);
                        updateFavoriteButton(activeTrigger);
                        buildChips(activeTrigger);
                        showFeedback(payload.message || 'Favorito actualizado.');
                    } catch (error) {
                        showFeedback(error?.message || 'No se pudo actualizar favorito.', true);
                    } finally {
                        favoriteButton.disabled = false;
                    }
                });
            }

            closeButtons.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            if (geoCopyButton) {
                geoCopyButton.addEventListener('click', async () => {
                    if (!currentCoordsText) {
                        geoCopyButton.textContent = 'Sin coordenadas';
                        geoCopyButton.classList.add('copied');
                        setTimeout(() => {
                            geoCopyButton.textContent = 'Copiar coordenadas';
                            geoCopyButton.classList.remove('copied');
                        }, 1200);
                        return;
                    }

                    try {
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            await navigator.clipboard.writeText(currentCoordsText);
                        } else {
                            const helper = document.createElement('textarea');
                            helper.value = currentCoordsText;
                            helper.setAttribute('readonly', '');
                            helper.style.position = 'absolute';
                            helper.style.left = '-9999px';
                            document.body.appendChild(helper);
                            helper.select();
                            document.execCommand('copy');
                            document.body.removeChild(helper);
                        }

                        geoCopyButton.textContent = 'Copiado';
                        geoCopyButton.classList.add('copied');
                    } catch (error) {
                        geoCopyButton.textContent = 'No se pudo copiar';
                    }

                    setTimeout(() => {
                        geoCopyButton.textContent = 'Copiar coordenadas';
                        geoCopyButton.classList.remove('copied');
                    }, 1300);
                });
            }

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) {
                    closeModal();
                }
            });
        })();
    </script>
@endsection

