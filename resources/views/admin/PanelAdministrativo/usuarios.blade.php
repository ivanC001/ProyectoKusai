@extends('layouts.admin')

@section('title', 'Admin | Administrar usuarios')
@section('page_title', 'Administrar usuarios')
@section('page_subtitle', 'Gestion de cuentas, bloqueo y eliminacion')

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
        margin-bottom: 12px;
    }
    .card {
        border: 1px solid #cfdbd3;
        border-radius: 12px;
        background: #fff;
        padding: 12px;
    }
    .card h3 {
        margin: 0 0 6px;
        font-size: .76rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #184632;
    }
    .value {
        margin: 0;
        color: #1c4f3a;
        font-size: 1.3rem;
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
        grid-template-columns: minmax(280px, 340px) 1fr;
        gap: 12px;
    }
    .panel {
        background: #fff;
        border: 1px solid #cfdbd3;
        border-radius: 14px;
        padding: 14px;
    }
    .panel h2 {
        margin: 0 0 6px;
        font-size: 1.05rem;
        color: #184632;
    }
    .panel p {
        margin: 0 0 10px;
        color: #5f7f71;
        font-size: .83rem;
    }
    .create-form {
        display: grid;
        gap: 8px;
    }
    .field {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .field label {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #587669;
        font-weight: 800;
    }
    .field input,
    .field select {
        border: 1px solid #ccd8d1;
        border-radius: 8px;
        padding: 8px 9px;
        font: inherit;
        color: #173f30;
        background: #f7fbf9;
    }
    .grid-two {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .error-text {
        color: #a32c2c;
        font-weight: 700;
        font-size: .74rem;
    }
    .table-wrap {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 880px;
    }
    .table th,
    .table td {
        border-bottom: 1px solid #d6e1db;
        text-align: left;
        padding: 12px 10px;
        font-size: .88rem;
        vertical-align: middle;
    }
    .table th {
        font-size: .79rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #5d7d6f;
        font-weight: 800;
    }
    .table tbody tr:hover {
        background: #f8fcfa;
    }
    .tag {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: .82rem;
        font-weight: 800;
        border: 1px solid transparent;
    }
    .tag.id {
        min-width: 34px;
        justify-content: center;
        padding: 7px 10px;
        background: #ecf5ef;
        border-color: #c9ddd0;
        color: #1c5940;
    }
    .tag.role-admin {
        background: #eef3ff;
        border-color: #cbd8f0;
        color: #3657a0;
    }
    .tag.role-cliente {
        background: #eef7f2;
        border-color: #c9ddcf;
        color: #2f6748;
    }
    .tag.role-agente {
        background: #f8f5ed;
        border-color: #e2dac5;
        color: #7d6537;
    }
    .tag.active {
        background: #e7f6ee;
        border-color: #badcc8;
        color: #1f6b47;
    }
    .tag.inactive {
        background: #faeeee;
        border-color: #ebcccc;
        color: #994444;
    }
    .actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .actions form {
        margin: 0;
    }
    .btn {
        border: 1px solid transparent;
        border-radius: 12px;
        font: inherit;
        font-size: .83rem;
        font-weight: 800;
        padding: 8px 14px;
        cursor: pointer;
    }
    .btn-main {
        color: #fff;
        background: linear-gradient(130deg, #1f8b58, #124c35);
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
        background: #a73d3d;
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
        gap: 7px;
        flex-wrap: wrap;
    }
    .pager a,
    .pager span {
        min-width: 35px;
        height: 32px;
        border-radius: 9px;
        border: 1px solid #ccd9d1;
        background: #fff;
        color: #2f5948;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 8px;
        text-decoration: none;
        font-weight: 800;
        font-size: .82rem;
    }
    .pager span.current {
        color: #fff;
        background: #17573c;
        border-color: #17573c;
    }
    @media (max-width: 1180px) {
        .cards {
            grid-template-columns: 1fr;
        }
        .grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 760px) {
        .grid-two {
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
            <h3>Usuarios registrados</h3>
            <p class="value">{{ number_format((int) $metricas['usuarios_total'], 0, '.', ',') }}</p>
            <p class="sub">Total de cuentas en la plataforma.</p>
        </article>
        <article class="card">
            <h3>Usuarios activos</h3>
            <p class="value">{{ number_format((int) $metricas['usuarios_activos'], 0, '.', ',') }}</p>
            <p class="sub">Con acceso habilitado al sistema.</p>
        </article>
        <article class="card">
            <h3>Usuarios inactivos</h3>
            <p class="value">{{ number_format((int) $metricas['usuarios_inactivos'], 0, '.', ',') }}</p>
            <p class="sub">Bloqueados temporalmente.</p>
        </article>
    </section>

    <div class="grid">
        <section class="panel">
            <h2>Nuevo usuario</h2>
            <p>Registro rapido de cuentas para administracion interna.</p>

            <form class="create-form" action="{{ route('admin.PanelAdministrativo.usuarios.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="create_name">Nombre</label>
                    <input id="create_name" name="name" type="text" value="{{ old('name') }}" placeholder="Nombre">
                    @error('name') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label for="create_apellidos">Apellidos</label>
                    <input id="create_apellidos" name="apellidos" type="text" value="{{ old('apellidos') }}" placeholder="Apellidos">
                    @error('apellidos') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label for="create_email">Correo</label>
                    <input id="create_email" name="email" type="email" value="{{ old('email') }}" placeholder="usuario@correo.com">
                    @error('email') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="field">
                    <label for="create_password">Clave temporal</label>
                    <input id="create_password" name="password" type="password" placeholder="Minimo 8 caracteres">
                    @error('password') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="grid-two">
                    <div class="field">
                        <label for="create_rol">Rol</label>
                        <select id="create_rol" name="rol">
                            @foreach (['admin' => 'Admin', 'agente' => 'Agente', 'cliente' => 'Cliente'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('rol', 'cliente') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="create_estado">Estado</label>
                        <select id="create_estado" name="estado">
                            @foreach (['activo' => 'Activo', 'inactivo' => 'Inactivo'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('estado', 'activo') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="create_tipo_persona">Tipo de persona</label>
                    <select id="create_tipo_persona" name="tipo_persona">
                        @foreach (['natural' => 'Natural', 'empresa' => 'Empresa'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('tipo_persona', 'natural') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-main">Crear usuario</button>
            </form>
        </section>

        <section class="panel">
            <h2>Usuarios registrados</h2>
            <p>Bloquea o elimina usuarios desde esta vista administrativa.</p>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Publicaciones</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            @php
                                $nombre = trim(($usuario->name ?? '').' '.($usuario->apellidos ?? ''));
                                $esMismoAdmin = auth()->id() === $usuario->id;
                            @endphp
                            <tr>
                                <td><span class="tag id">{{ $usuario->id }}</span></td>
                                <td>{{ $nombre !== '' ? $nombre : 'Sin nombre' }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td><span class="tag role-{{ $usuario->rol }}">{{ ucfirst($usuario->rol) }}</span></td>
                                <td>
                                    <span class="tag {{ $usuario->estado === 'activo' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </td>
                                <td>{{ $usuario->propiedades_count }}</td>
                                <td>{{ $usuario->created_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="actions">
                                        @if ($esMismoAdmin)
                                            <button class="btn btn-disabled" type="button" disabled>Tu cuenta</button>
                                        @else
                                            <form method="POST" action="{{ route('admin.PanelAdministrativo.usuarios.estado.update', $usuario) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="estado" value="{{ $usuario->estado === 'activo' ? 'inactivo' : 'activo' }}">
                                                <button type="submit" class="btn {{ $usuario->estado === 'activo' ? 'btn-block' : 'btn-activate' }}">
                                                    {{ $usuario->estado === 'activo' ? 'Bloquear' : 'Activar' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.PanelAdministrativo.usuarios.destroy', $usuario) }}" onsubmit="return confirm('Se eliminara este usuario y sus datos asociados. Deseas continuar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($usuarios->lastPage() > 1)
                <nav class="pager" aria-label="Paginacion de usuarios en panel administrativo">
                    @if ($usuarios->onFirstPage())
                        <span>&lt;</span>
                    @else
                        <a href="{{ $usuarios->previousPageUrl() }}">&lt;</a>
                    @endif

                    @for ($page = 1; $page <= $usuarios->lastPage(); $page++)
                        @if ($page === $usuarios->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $usuarios->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($usuarios->hasMorePages())
                        <a href="{{ $usuarios->nextPageUrl() }}">&gt;</a>
                    @else
                        <span>&gt;</span>
                    @endif
                </nav>
            @endif
        </section>
    </div>
@endsection
