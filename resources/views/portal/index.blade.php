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
                                <span class="meta-chip" data-favoritos-id="{{ $propiedad->id }}">{{ $propiedad->favoritos_count }} favorito(s)</span>
                            </div>
                            <div class="card-actions">
                                @auth
                                    <button
                                        type="button"
                                        class="card-favorite-btn {{ $favoritasIds->contains($propiedad->id) ? 'active' : '' }}"
                                        data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                        data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                        data-favoritos-id="{{ $propiedad->id }}"
                                        aria-label="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                        aria-pressed="{{ $favoritasIds->contains($propiedad->id) ? 'true' : 'false' }}"
                                        title="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                    >
                                        {!! $favoritasIds->contains($propiedad->id) ? '&#9829;' : '&#9825;' !!}
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="card-favorite-btn" title="Inicia sesion para guardar favoritos">&#9825;</a>
                                @endauth
                                <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">
                                    Ver detalle
                                </a>
                            </div>
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
                                    <span class="meta-chip" data-favoritos-id="{{ $propiedad->id }}">{{ $propiedad->favoritos_count }} favorito(s)</span>
                                </div>
                                <div class="card-actions">
                                    @auth
                                        <button
                                            type="button"
                                            class="card-favorite-btn {{ $favoritasIds->contains($propiedad->id) ? 'active' : '' }}"
                                            data-favorito-url="{{ route('portal.propiedades.favoritos.toggle', $propiedad) }}"
                                            data-favorita="{{ $favoritasIds->contains($propiedad->id) ? '1' : '0' }}"
                                            data-favoritos-id="{{ $propiedad->id }}"
                                            aria-label="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                            aria-pressed="{{ $favoritasIds->contains($propiedad->id) ? 'true' : 'false' }}"
                                            title="{{ $favoritasIds->contains($propiedad->id) ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                                        >
                                            {!! $favoritasIds->contains($propiedad->id) ? '&#9829;' : '&#9825;' !!}
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="card-favorite-btn" title="Inicia sesion para guardar favoritos">&#9825;</a>
                                    @endauth
                                    <a class="card-link" href="{{ route('portal.propiedades.show', $propiedad) }}">
                                        Ver detalle
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
                        button.innerHTML = favorita ? '&#9829;' : '&#9825;';
                        button.setAttribute('aria-label', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                        button.setAttribute('title', favorita ? 'Quitar de favoritos' : 'Agregar a favoritos');
                        button.setAttribute('aria-pressed', favorita ? 'true' : 'false');

                        const favoritosId = button.dataset.favoritosId || '';
                        if (favoritosId !== '') {
                            document.querySelectorAll(`[data-favoritos-id="${favoritosId}"]`).forEach((counter) => {
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



