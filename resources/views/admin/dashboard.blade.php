@extends('layouts.admin')

@section('title', 'Admin | Panel')
@section('page_title', 'Panel administrativo')
@section('page_subtitle', 'Visitas del portal y rendimiento de publicaciones')

@section('styles')
<style>
    .alerts {
        display: grid;
        gap: 10px;
        margin-bottom: 12px;
    }
    .alert {
        border-radius: 11px;
        border: 1px solid;
        padding: 10px 12px;
        font-size: .9rem;
        font-weight: 700;
    }
    .alert.success {
        background: #ddf6e8;
        color: #0f5739;
        border-color: #abdbc0;
    }
    .alert.error {
        background: #fce9e9;
        color: #9a3333;
        border-color: #efc8c8;
    }
    .cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 16px;
    }
    .card {
        background: #fff;
        border: 1px solid #cfdbd3;
        border-radius: 14px;
        padding: 16px;
    }
    .card h3 {
        margin: 0 0 8px;
        color: #184632;
        font-size: .88rem;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .value {
        margin: 0;
        color: #1c4f3a;
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
    }
    .sub {
        margin: 6px 0 0;
        color: #5f7f71;
        font-size: .84rem;
    }
    .grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 14px;
        margin-bottom: 16px;
    }
    .panel {
        background: #fff;
        border: 1px solid #cfdbd3;
        border-radius: 14px;
        padding: 16px;
    }
    .panel + .panel {
        margin-top: 14px;
    }
    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .panel-head h2 {
        margin: 0;
        font-size: 1.06rem;
        color: #1a4936;
    }
    .panel-head p {
        margin: 4px 0 0;
        color: #5f7f71;
        font-size: .9rem;
    }
    .panel-link {
        font-weight: 700;
        color: #156e47;
        text-decoration: none;
    }
    .table-wrap {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 860px;
    }
    .table.small {
        min-width: 420px;
    }
    .table th,
    .table td {
        border-bottom: 1px solid #d9e3dd;
        text-align: left;
        padding: 10px 8px;
        font-size: .9rem;
    }
    .table th {
        color: #607f72;
        font-size: .76rem;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        border: 1px solid #c9d7ce;
        background: #f3f8f5;
        color: #365b4c;
        font-size: .76rem;
        font-weight: 800;
        padding: 4px 9px;
    }
    .chip.active {
        border-color: #b5d9c3;
        background: #e7f6ee;
        color: #1f6b47;
    }
    .chip.inactive {
        border-color: #ebcccc;
        background: #faeeee;
        color: #994444;
    }
    .chip.role-admin {
        border-color: #c6d6f3;
        background: #eef3ff;
        color: #3558a3;
    }
    .chip.role-cliente {
        border-color: #c8dccf;
        background: #eef7f2;
        color: #2f6748;
    }
    .chip.role-agente {
        border-color: #d9d3c5;
        background: #f8f5ed;
        color: #7d6537;
    }
    .list {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 10px;
    }
    .list-item {
        border: 1px solid #d3dfd8;
        border-radius: 12px;
        padding: 10px 11px;
        background: #fafdfb;
    }
    .list-title {
        margin: 0;
        color: #1c4d3a;
        font-weight: 800;
        font-size: .93rem;
    }
    .list-sub {
        margin: 3px 0 0;
        color: #658478;
        font-size: .82rem;
    }
    .actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .actions form {
        margin: 0;
    }
    .btn {
        border: 1px solid transparent;
        border-radius: 9px;
        font: inherit;
        font-size: .8rem;
        font-weight: 800;
        padding: 7px 10px;
        cursor: pointer;
    }
    .btn-block {
        color: #9a3434;
        background: #fff1f1;
        border-color: #ebcaca;
    }
    .btn-activate {
        color: #1f6242;
        background: #edf9f2;
        border-color: #c4e0cf;
    }
    .btn-delete {
        color: #fff;
        background: #9c3a3a;
    }
    .btn-disabled {
        color: #698478;
        background: #f2f7f4;
        border-color: #d4dfd8;
        cursor: not-allowed;
    }
    .pager {
        margin-top: 12px;
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .pager a,
    .pager span {
        min-width: 40px;
        height: 36px;
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
        font-size: .86rem;
    }
    .pager span.current {
        color: #fff;
        background: #17573c;
        border-color: #17573c;
    }
    @media (max-width: 1100px) {
        .cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 700px) {
        .cards {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    @if (session('success') || session('error'))
        <div class="alerts">
            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert error">{{ session('error') }}</div>
            @endif
        </div>
    @endif

    <section class="cards">
        <article class="card">
            <h3>Usuarios Totales</h3>
            <p class="value">{{ number_format((int) $metricas['usuarios_total'], 0, '.', ',') }}</p>
            <p class="sub">
                Admin: {{ number_format((int) $metricas['usuarios_admin'], 0, '.', ',') }} |
                Cliente: {{ number_format((int) $metricas['usuarios_cliente'], 0, '.', ',') }} |
                Agente: {{ number_format((int) $metricas['usuarios_agente'], 0, '.', ',') }}
            </p>
        </article>
        <article class="card">
            <h3>Estado De Usuarios</h3>
            <p class="value">{{ number_format((int) $metricas['usuarios_activos'], 0, '.', ',') }}</p>
            <p class="sub">Activos | Inactivos: {{ number_format((int) $metricas['usuarios_inactivos'], 0, '.', ',') }}</p>
        </article>
        <article class="card">
            <h3>Visitas Al Portal</h3>
            <p class="value">{{ number_format((int) $metricas['visitas_portal_total'], 0, '.', ',') }}</p>
            <p class="sub">
                Hoy: {{ number_format((int) $metricas['visitas_portal_hoy'], 0, '.', ',') }} |
                Unicos: {{ number_format((int) $metricas['visitantes_unicos_portal'], 0, '.', ',') }}
            </p>
        </article>
        <article class="card">
            <h3>Clics En Propiedades</h3>
            <p class="value">{{ number_format((int) $metricas['clics_propiedades_total'], 0, '.', ',') }}</p>
            <p class="sub">Publicaciones totales: {{ number_format((int) $metricas['propiedades_total'], 0, '.', ',') }}</p>
        </article>
    </section>

    <section class="grid">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Visitas del portal (ultimos 7 dias)</h2>
                    <p>Resumen diario de trafico para revisar tendencia reciente.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table small">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Visitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visitasUltimos7Dias as $row)
                            <tr>
                                <td>{{ $row['label'] }}</td>
                                <td>{{ $row['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Publicaciones con mayores clics</h2>
                    <p>Top actual por clics con apoyo de favoritos para desempatar.</p>
                </div>
                <a class="panel-link" href="{{ route('admin.PanelAdministrativo') }}">Ir a administrar tipo de terrenos</a>
            </div>

            <ul class="list">
                @forelse ($topPublicaciones as $propiedadTop)
                    <li class="list-item">
                        <p class="list-title">{{ $propiedadTop->titulo }}</p>
                        <p class="list-sub">
                            {{ trim(($propiedadTop->usuario?->name ?? '').' '.($propiedadTop->usuario?->apellidos ?? '')) ?: 'Sin usuario' }}
                            |
                            {{ $propiedadTop->visitas_count }} clic(s)
                            |
                            {{ $propiedadTop->favoritos_count }} favorito(s)
                        </p>
                    </li>
                @empty
                    <li class="list-item">
                        <p class="list-title">Sin publicaciones</p>
                        <p class="list-sub">Aun no hay datos para el ranking.</p>
                    </li>
                @endforelse
            </ul>
        </article>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Rendimiento por publicacion</h2>
                <p>Cantidad de clics, favoritos y contactos por propiedad.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titulo</th>
                        <th>Propietario</th>
                        <th>Tipo</th>
                        <th>Ubicacion</th>
                        <th>Clics</th>
                        <th>Favoritos</th>
                        <th>Contactos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($publicaciones as $propiedad)
                        <tr>
                            <td><span class="chip">{{ $propiedad->id }}</span></td>
                            <td>{{ $propiedad->titulo }}</td>
                            <td>{{ trim(($propiedad->usuario?->name ?? '').' '.($propiedad->usuario?->apellidos ?? '')) ?: 'Sin usuario' }}</td>
                            <td>{{ $propiedad->tipoPropiedad?->nombre ?? 'Sin tipo' }}</td>
                            <td>{{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }}, {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}</td>
                            <td>{{ $propiedad->visitas_count }}</td>
                            <td>{{ $propiedad->favoritos_count }}</td>
                            <td>{{ $propiedad->contactos_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No hay publicaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($publicaciones->lastPage() > 1)
            <nav class="pager" aria-label="Paginacion de publicaciones en admin">
                @if ($publicaciones->onFirstPage())
                    <span>&lt;</span>
                @else
                    <a href="{{ $publicaciones->previousPageUrl() }}">&lt;</a>
                @endif

                @for ($page = 1; $page <= $publicaciones->lastPage(); $page++)
                    @if ($page === $publicaciones->currentPage())
                        <span class="current">{{ $page }}</span>
                    @else
                        <a href="{{ $publicaciones->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($publicaciones->hasMorePages())
                    <a href="{{ $publicaciones->nextPageUrl() }}">&gt;</a>
                @else
                    <span>&gt;</span>
                @endif
            </nav>
        @endif
    </section>

@endsection
