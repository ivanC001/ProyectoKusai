@extends('layouts.client')

@section('title', 'Mis publicaciones | Kusay.pe')

@section('styles')
<style>
    .mine-wrap {
        width: min(1180px, 94vw);
        margin: 0 auto;
    }
    .mine-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .mine-title {
        margin: 0;
        font-family: "Fraunces", serif;
        font-size: clamp(1.8rem, 3.8vw, 2.5rem);
        color: #164634;
    }
    .mine-subtitle {
        margin: 6px 0 0;
        color: #678577;
    }
    .btn-main {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid transparent;
        padding: 10px 14px;
        font-weight: 800;
        color: #fff;
        background: linear-gradient(130deg, #2d8f5f, #17573c);
        text-decoration: none;
    }
    .alert-success {
        border-radius: 12px;
        border: 1px solid #abdbc0;
        background: #ddf6e8;
        color: #0f5739;
        padding: 11px 13px;
        margin-bottom: 16px;
        font-weight: 700;
    }
    .alert-error {
        border-radius: 12px;
        border: 1px solid #f2c4c4;
        background: #fce8e8;
        color: #992e2e;
        padding: 11px 13px;
        margin-bottom: 16px;
        font-weight: 700;
    }
    .stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }
    .stat-card {
        border-radius: 14px;
        border: 1px solid #d4e1d9;
        background: #fff;
        padding: 14px;
    }
    .stat-label {
        margin: 0 0 6px;
        color: #6b8a7d;
        font-size: .78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .stat-value {
        margin: 0;
        color: #1e4a39;
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
    }
    .filters {
        border-radius: 14px;
        border: 1px solid #d4e1d9;
        background: #fff;
        padding: 12px;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 10px;
        margin-bottom: 16px;
    }
    .filters label {
        display: block;
        font-size: .76rem;
        font-weight: 800;
        letter-spacing: .05em;
        color: #5f7f71;
        margin-bottom: 4px;
    }
    .filters select {
        width: 100%;
        border: 1px solid #ccd9d1;
        border-radius: 10px;
        padding: 10px 11px;
        background: #f6faf8;
        color: #274d3d;
        font: inherit;
    }
    .filters-actions {
        display: flex;
        align-items: end;
        gap: 8px;
    }
    .btn-filter,
    .btn-clear {
        border-radius: 10px;
        border: 1px solid transparent;
        padding: 10px 12px;
        font: inherit;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-filter {
        color: #fff;
        background: #194f38;
    }
    .btn-clear {
        color: #315d4b;
        border-color: #c6d5cc;
        background: #f8fbf9;
    }
    .grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }
    .card {
        border-radius: 14px;
        border: 1px solid #d4e1d9;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(17, 58, 40, .08);
    }
    .cover {
        height: 180px;
        width: 100%;
        object-fit: cover;
        background: #dce9e2;
        display: block;
    }
    .cover-empty {
        height: 180px;
        display: grid;
        place-items: center;
        background: linear-gradient(145deg, #dce9e2, #eaf3ee);
        color: #4f7363;
        font-weight: 700;
        font-size: .95rem;
    }
    .card-body {
        padding: 12px;
    }
    .card-title {
        margin: 0 0 5px;
        color: #194634;
        font-size: 1rem;
        font-weight: 800;
    }
    .meta {
        margin: 0 0 8px;
        color: #678577;
        font-size: .88rem;
    }
    .price {
        margin: 0 0 10px;
        color: #aa4a28;
        font-family: "Fraunces", serif;
        font-size: 1.32rem;
        font-weight: 800;
    }
    .chips {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .chip {
        border-radius: 999px;
        border: 1px solid #cddad2;
        background: #f3f8f5;
        color: #416757;
        font-size: .74rem;
        font-weight: 800;
        padding: 4px 8px;
    }
    .chip-state {
        border-color: transparent;
        color: #fff;
    }
    .state-disponible { background: #2c8d5e; }
    .state-reservado { background: #db9b2b; }
    .state-vendido { background: #8b9aa3; }

    .card-actions {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 8px;
        align-items: center;
    }
    .card-actions form {
        margin: 0;
    }
    .state-form {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .state-form select {
        flex: 1;
        border: 1px solid #ccd9d1;
        border-radius: 9px;
        padding: 8px 9px;
        background: #f7fbf9;
        color: #244b3b;
        font: inherit;
    }
    .btn-save {
        border: none;
        border-radius: 9px;
        background: #1a5b40;
        color: #fff;
        font: inherit;
        font-size: .82rem;
        font-weight: 800;
        padding: 8px 10px;
        cursor: pointer;
    }
    .btn-delete {
        border: 1px solid #edcbcb;
        border-radius: 9px;
        background: #fff4f4;
        color: #9a3434;
        font: inherit;
        font-size: .82rem;
        font-weight: 800;
        padding: 8px 10px;
        cursor: pointer;
    }
    .card-ops {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-edit {
        border: 1px solid #c6d7ce;
        border-radius: 9px;
        background: #f8fbf9;
        color: #1f553f;
        font-size: .82rem;
        font-weight: 800;
        padding: 8px 10px;
        text-decoration: none;
    }
    .empty {
        border-radius: 14px;
        border: 1px dashed #b9cec2;
        background: #f7fbf8;
        padding: 26px;
        text-align: center;
    }
    .empty h3 {
        margin: 0 0 8px;
        font-family: "Fraunces", serif;
        color: #194634;
    }
    .empty p {
        margin: 0 0 12px;
        color: #678577;
    }
    .pager {
        margin-top: 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .pager a,
    .pager span {
        min-width: 40px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #ccd9d1;
        background: #fff;
        color: #2f5948;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        text-decoration: none;
        font-weight: 800;
        font-size: .88rem;
    }
    .pager span.current {
        color: #fff;
        background: #17573c;
        border-color: #17573c;
    }
    @media (max-width: 980px) {
        .stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .filters {
            grid-template-columns: 1fr 1fr;
        }
        .filters-actions {
            grid-column: span 2;
        }
        .grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 700px) {
        .stats,
        .filters,
        .grid {
            grid-template-columns: 1fr;
        }
        .filters-actions {
            grid-column: span 1;
        }
        .card-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    <div class="mine-wrap">
        <header class="mine-head">
            <div>
                <h1 class="mine-title">Mis publicaciones</h1>
                <p class="mine-subtitle">Gestiona tus propiedades, cambia estado y elimina avisos cuando lo necesites.</p>
            </div>
            <a href="{{ route('propiedades.create') }}" class="btn-main">+ Publicar nueva propiedad</a>
        </header>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <section class="stats">
            <article class="stat-card">
                <p class="stat-label">Total</p>
                <p class="stat-value">{{ $estadisticas['total'] }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Disponibles</p>
                <p class="stat-value">{{ $estadisticas['disponibles'] }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Reservadas</p>
                <p class="stat-value">{{ $estadisticas['reservadas'] }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Vendidas</p>
                <p class="stat-value">{{ $estadisticas['vendidas'] }}</p>
            </article>
        </section>

        <form class="filters" method="GET" action="{{ route('propiedades.mine') }}">
            <div>
                <label for="estado">ESTADO</label>
                <select id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="disponible" @selected($filtros['estado'] === 'disponible')>Disponible</option>
                    <option value="reservado" @selected($filtros['estado'] === 'reservado')>Reservado</option>
                    <option value="vendido" @selected($filtros['estado'] === 'vendido')>Vendido</option>
                </select>
            </div>

            <div>
                <label for="tipo">OPERACION</label>
                <select id="tipo" name="tipo">
                    <option value="">Todas</option>
                    <option value="venta" @selected($filtros['tipo'] === 'venta')>Venta</option>
                    <option value="alquiler" @selected($filtros['tipo'] === 'alquiler')>Alquiler</option>
                </select>
            </div>

            <div>
                <label for="orden">ORDENAR</label>
                <select id="orden" name="orden">
                    <option value="recientes" @selected($filtros['orden'] === 'recientes')>Mas recientes</option>
                    <option value="antiguas" @selected($filtros['orden'] === 'antiguas')>Mas antiguas</option>
                    <option value="precio_asc" @selected($filtros['orden'] === 'precio_asc')>Menor precio</option>
                    <option value="precio_desc" @selected($filtros['orden'] === 'precio_desc')>Mayor precio</option>
                </select>
            </div>

            <div class="filters-actions">
                <button class="btn-filter" type="submit">Aplicar</button>
                <a class="btn-clear" href="{{ route('propiedades.mine') }}">Limpiar</a>
            </div>
        </form>

        @if ($propiedades->isEmpty())
            <section class="empty">
                <h3>No tienes publicaciones aun</h3>
                <p>Empieza con tu primer inmueble para aparecer en el portal.</p>
                <a href="{{ route('propiedades.create') }}" class="btn-main">Publicar mi primera propiedad</a>
            </section>
        @else
            <section class="grid">
                @foreach ($propiedades as $propiedad)
                    @php
                        $portada = $propiedad->portadaImagen;
                        $estadoClase = match ($propiedad->estado) {
                            'reservado' => 'state-reservado',
                            'vendido' => 'state-vendido',
                            default => 'state-disponible',
                        };
                    @endphp

                    <article class="card">
                        @if ($portada)
                            <img class="cover" src="{{ route('propiedades.imagen.show', [$propiedad, $portada]) }}" alt="Portada de {{ $propiedad->titulo }}">
                        @else
                            <div class="cover-empty">Sin foto de portada</div>
                        @endif

                        <div class="card-body">
                            <h3 class="card-title">{{ $propiedad->titulo }}</h3>
                            <p class="meta">
                                {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                                {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                            </p>
                            <p class="price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>

                            <div class="chips">
                                <span class="chip chip-state {{ $estadoClase }}">{{ ucfirst($propiedad->estado) }}</span>
                                <span class="chip">{{ ucfirst($propiedad->tipo) }}</span>
                                <span class="chip">{{ $propiedad->tipoPropiedad?->nombre ?? 'Sin tipo' }}</span>
                                <span class="chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                                <span class="chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                            </div>

                            <div class="card-actions">
                                <form class="state-form" method="POST" action="{{ route('propiedades.estado.update', $propiedad) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="estado" aria-label="Estado de la propiedad {{ $propiedad->id }}">
                                        <option value="disponible" @selected($propiedad->estado === 'disponible')>Disponible</option>
                                        <option value="reservado" @selected($propiedad->estado === 'reservado')>Reservado</option>
                                        <option value="vendido" @selected($propiedad->estado === 'vendido')>Vendido</option>
                                    </select>
                                    <button class="btn-save" type="submit">Guardar</button>
                                </form>

                                <div class="card-ops">
                                    <a class="btn-edit" href="{{ route('propiedades.edit', $propiedad) }}">Editar</a>
                                    <form method="POST" action="{{ route('propiedades.destroy', $propiedad) }}" onsubmit="return confirm('Esta accion eliminara la publicacion. Deseas continuar?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            @if ($propiedades->lastPage() > 1)
                <nav class="pager" aria-label="Paginacion de mis publicaciones">
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
    </div>
@endsection
