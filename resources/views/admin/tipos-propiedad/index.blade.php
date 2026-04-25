@extends('layouts.admin')

@section('title', 'Admin | Tipos de propiedad')
@section('page_title', 'Tipos de propiedad')
@section('page_subtitle', 'Registro y listado de tipos para las publicaciones')

@section('styles')
<style>
    .layout {
        display: grid;
        grid-template-columns: minmax(300px, 380px) 1fr;
        gap: 16px;
    }
    .panel {
        background: #fff;
        border: 1px solid #cfdbd3;
        border-radius: 14px;
        padding: 16px;
    }
    .panel h2 {
        margin: 0 0 8px;
        font-size: 1.05rem;
    }
    .panel p {
        margin: 0 0 14px;
        color: #5f7f71;
        font-size: .92rem;
    }
    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 10px;
    }
    .field label {
        font-size: .77rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #587669;
        font-weight: 800;
    }
    .field input {
        border: 1px solid #ccd8d1;
        border-radius: 10px;
        padding: 10px 11px;
        font: inherit;
        color: #173f30;
        background: #f7fbf9;
    }
    .error-text {
        color: #a32c2c;
        font-weight: 700;
        font-size: .8rem;
    }
    .btn {
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 800;
        cursor: pointer;
    }
    .btn-main {
        color: #fff;
        background: linear-gradient(130deg, #1f8b58, #124c35);
    }
    .alert {
        border: 1px solid;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 10px;
        font-weight: 600;
        font-size: .9rem;
    }
    .alert.success {
        background: #ddf5e8;
        color: #0d5c3a;
        border-color: #acd7bf;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th,
    .table td {
        border-bottom: 1px solid #d6e1db;
        text-align: left;
        padding: 10px 8px;
    }
    .table th {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #5d7d6f;
    }
    .tag {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 999px;
        background: #e9f5ee;
        color: #186d47;
        font-size: .78rem;
        font-weight: 700;
    }
    @media (max-width: 980px) {
        .layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    <div class="layout">
        <section class="panel">
            <h2>Nuevo tipo de propiedad</h2>
            <p>Este catalogo se usa en el formulario de registro de propiedades.</p>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.tipos-propiedad.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="nombre">Nombre del tipo</label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" placeholder="Ej: Casa, Terreno, Departamento">
                    @error('nombre') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-main">Guardar tipo</button>
            </form>
        </section>

        <section class="panel">
            <h2>Listado de tipos</h2>
            <p>Total registrados: {{ $tiposPropiedad->count() }}</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Creado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tiposPropiedad as $tipo)
                        <tr>
                            <td><span class="tag">{{ $tipo->id }}</span></td>
                            <td>{{ $tipo->nombre }}</td>
                            <td>{{ $tipo->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No hay tipos registrados todavia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
@endsection
