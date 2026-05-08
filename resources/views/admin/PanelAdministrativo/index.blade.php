@extends('layouts.admin')

@section('title', 'Admin | Administrar tipo de terrenos')
@section('page_title', 'Administrar tipo de terrenos')
@section('page_subtitle', 'Catalogo ordenado para el formulario de publicaciones')

@section('styles')
<style>
    .alerts {
        display: grid;
        gap: 8px;
        margin-bottom: 14px;
    }
    .alert {
        border-radius: 10px;
        border: 1px solid;
        padding: 8px 10px;
        font-size: .84rem;
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
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }
    .card {
        border: 1px solid #cfdbd3;
        border-radius: 12px;
        background: #fff;
        padding: 13px;
    }
    .card h3 {
        margin: 0 0 6px;
        font-size: .77rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #184632;
    }
    .value {
        margin: 0;
        color: #1c4f3a;
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1;
    }
    .sub {
        margin: 5px 0 0;
        color: #5f7f71;
        font-size: .8rem;
    }
    .grid {
        display: grid;
        grid-template-columns: minmax(300px, 360px) 1fr;
        gap: 12px;
    }
    .panel {
        background: #fff;
        border: 1px solid #cfdbd3;
        border-radius: 14px;
        padding: 14px;
    }
    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
    }
    .panel-head h2 {
        margin: 0;
        font-size: 1.03rem;
        color: #184632;
    }
    .panel-head span {
        border: 1px solid #d2e0d8;
        border-radius: 999px;
        padding: 3px 9px;
        font-size: .75rem;
        font-weight: 800;
        color: #4f7163;
        background: #f4f9f6;
    }
    .panel p {
        margin: 0 0 10px;
        color: #5f7f71;
        font-size: .83rem;
    }
    .field {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 9px;
    }
    .field label {
        font-size: .73rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #587669;
        font-weight: 800;
    }
    .field input {
        border: 1px solid #ccd8d1;
        border-radius: 8px;
        padding: 9px 10px;
        font: inherit;
        color: #173f30;
        background: #f7fbf9;
    }
    .field select,
    .field textarea {
        border: 1px solid #ccd8d1;
        border-radius: 8px;
        padding: 9px 10px;
        font: inherit;
        color: #173f30;
        background: #f7fbf9;
        width: 100%;
    }
    .field textarea {
        resize: vertical;
        min-height: 260px;
    }
    .field-help {
        margin-top: 3px;
        color: #628376;
        font-size: .76rem;
        line-height: 1.4;
    }
    .error-text {
        color: #a32c2c;
        font-weight: 700;
        font-size: .76rem;
    }
    .btn {
        border: 1px solid transparent;
        border-radius: 10px;
        font: inherit;
        font-size: .78rem;
        font-weight: 800;
        padding: 7px 11px;
        cursor: pointer;
    }
    .btn-main {
        color: #fff;
        background: linear-gradient(130deg, #1f8b58, #124c35);
    }
    .btn-edit {
        border-color: #c4d4ca;
        background: #f8fbf9;
        color: #205540;
    }
    .btn-delete {
        color: #fff;
        background: #9c3a3a;
    }
    .table-wrap {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }
    .table th,
    .table td {
        border-bottom: 1px solid #d6e1db;
        text-align: left;
        padding: 10px 8px;
        font-size: .86rem;
        vertical-align: middle;
    }
    .table th {
        font-size: .74rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #5d7d6f;
    }
    .table tbody tr:hover {
        background: #f8fcfa;
    }
    .tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 25px;
        height: 25px;
        padding: 0 8px;
        border-radius: 999px;
        background: #e9f5ee;
        color: #186d47;
        font-size: .75rem;
        font-weight: 700;
    }
    .row-form {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 7px;
        align-items: center;
        margin-bottom: 6px;
    }
    .row-form input {
        border: 1px solid #ccd8d1;
        border-radius: 8px;
        padding: 8px 9px;
        font: inherit;
        font-size: .84rem;
        color: #173f30;
        background: #f7fbf9;
        width: 100%;
    }
    .inline {
        margin: 0;
    }
    .list {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 8px;
    }
    .list-item {
        border: 1px solid #d3dfd8;
        border-radius: 10px;
        padding: 8px 9px;
        background: #fafdfb;
    }
    .list-title {
        margin: 0;
        color: #1c4d3a;
        font-weight: 800;
        font-size: .85rem;
    }
    .list-sub {
        margin: 2px 0 0;
        color: #658478;
        font-size: .77rem;
    }
    @media (max-width: 1120px) {
        .cards {
            grid-template-columns: 1fr;
        }
        .grid {
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
            <h3>Tipos registrados</h3>
            <p class="value">{{ number_format((int) $metricas['tipos_total'], 0, '.', ',') }}</p>
            <p class="sub">Catalogo total disponible.</p>
        </article>
        <article class="card">
            <h3>Propiedades asociadas</h3>
            <p class="value">{{ number_format((int) $metricas['propiedades_asociadas'], 0, '.', ',') }}</p>
            <p class="sub">Publicaciones vinculadas a estos tipos.</p>
        </article>
        <article class="card">
            <h3>Visitas y clics</h3>
            <p class="value">{{ number_format((int) $metricas['clics_propiedades_total'], 0, '.', ',') }}</p>
            <p class="sub">Visitas del portal: {{ number_format((int) $metricas['visitas_portal_total'], 0, '.', ',') }}</p>
        </article>
    </section>

    <div class="grid">
        <section class="panel">
            <div class="panel-head">
                <h2>Crear nuevo tipo</h2>
                <span>Orden alfabetico</span>
            </div>
            <p>Registra tipos de terreno o inmueble para el formulario de publicaciones.</p>

            <form action="{{ route('admin.PanelAdministrativo.tipos.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="nombre">Nombre del tipo</label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" placeholder="Ej: Terreno agricola, Casa de campo">
                    @error('nombre') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-main">Guardar tipo</button>
            </form>

            <hr style="border:0;border-top:1px solid #dce6e0;margin:14px 0;">

            <div class="panel-head">
                <h2>Top por clics</h2>
                <span>{{ $topPublicaciones->count() }} publicaciones</span>
            </div>
            <ul class="list">
                @forelse ($topPublicaciones as $item)
                    <li class="list-item">
                        <p class="list-title">{{ $item->titulo }}</p>
                        <p class="list-sub">{{ $item->visitas_count }} clic(s) | {{ $item->favoritos_count }} favorito(s)</p>
                    </li>
                @empty
                    <li class="list-item">
                        <p class="list-title">Sin datos</p>
                        <p class="list-sub">No hay publicaciones para mostrar.</p>
                    </li>
                @endforelse
            </ul>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Listado de tipos</h2>
                <span>{{ $tiposPropiedad->count() }} registros</span>
            </div>
            <p>Edita o elimina tipos de terreno desde esta tabla.</p>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Publicaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tiposPropiedad as $tipo)
                            <tr>
                                <td><span class="tag">{{ $tipo->id }}</span></td>
                                <td>{{ $tipo->nombre }}</td>
                                <td>{{ $tipo->propiedades_count }}</td>
                                <td>
                                    <form class="row-form" method="POST" action="{{ route('admin.PanelAdministrativo.tipos.update', $tipo) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="nombre" value="{{ $tipo->nombre }}" aria-label="Modificar tipo {{ $tipo->id }}">
                                        <button type="submit" class="btn btn-edit">Guardar</button>
                                    </form>
                                    <form class="inline" method="POST" action="{{ route('admin.PanelAdministrativo.tipos.destroy', $tipo) }}" onsubmit="return confirm('Se eliminara el tipo seleccionado. Deseas continuar?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay tipos registrados todavia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
