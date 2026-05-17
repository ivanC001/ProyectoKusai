@extends('layouts.admin')

@section('title', 'Admin | Sugerencias del portal')
@section('page_title', 'Sugerencias y reseñas de cómo publicar')
@section('page_subtitle', 'Moderación de comentarios públicos y lectura privada de sugerencias')

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
    .cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
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
    .table-wrap {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    .table th,
    .table td {
        border-bottom: 1px solid #d6e1db;
        text-align: left;
        padding: 10px 8px;
        font-size: .85rem;
        vertical-align: top;
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
    .feedback-user {
        color: #1a4b38;
        font-weight: 800;
    }
    .feedback-user small {
        display: block;
        color: #6b8a7c;
        font-size: .76rem;
        font-weight: 700;
        margin-top: 2px;
    }
    .stars {
        color: #d19a00;
        display: inline-flex;
        gap: 2px;
    }
    .text {
        color: #345a49;
        white-space: pre-line;
        line-height: 1.45;
    }
    .sugerencia {
        border: 1px solid #d4e0d9;
        background: #f5faf7;
        border-radius: 10px;
        padding: 7px 8px;
        color: #274e3d;
        white-space: pre-line;
    }
    .sugerencia.empty {
        color: #7a9488;
    }
    .tag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        padding: 4px 9px;
        font-size: .73rem;
        font-weight: 800;
        border: 1px solid;
    }
    .tag.visible {
        background: #e6f7ee;
        color: #166341;
        border-color: #9fd2b6;
    }
    .tag.hidden {
        background: #fff3e7;
        color: #8b5525;
        border-color: #efcda8;
    }
    .actions {
        display: grid;
        gap: 6px;
    }
    .btn {
        border: 1px solid transparent;
        border-radius: 9px;
        font: inherit;
        font-size: .78rem;
        font-weight: 800;
        padding: 6px 10px;
        cursor: pointer;
    }
    .btn-main {
        color: #fff;
        background: linear-gradient(130deg, #1f8b58, #124c35);
    }
    .btn-warn {
        color: #8b5525;
        background: #fff3e7;
        border-color: #efcda8;
    }
    .btn-delete {
        color: #fff;
        background: #9c3a3a;
    }
    .date {
        color: #6f8d80;
        font-size: .76rem;
        font-weight: 700;
    }
    @media (max-width: 1160px) {
        .cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 680px) {
        .cards {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alerts">
            <div class="alert success">{{ session('success') }}</div>
        </div>
    @endif

    <section class="cards">
        <article class="card">
            <h3>Total reseñas</h3>
            <p class="value">{{ number_format((int) $metricas['total'], 0, '.', ',') }}</p>
            <p class="sub">Mensajes recibidos en “Cómo publicar”.</p>
        </article>
        <article class="card">
            <h3>Visibles en portal</h3>
            <p class="value">{{ number_format((int) $metricas['visibles'], 0, '.', ',') }}</p>
            <p class="sub">Actualmente públicos para usuarios.</p>
        </article>
        <article class="card">
            <h3>Ocultos</h3>
            <p class="value">{{ number_format((int) $metricas['ocultos'], 0, '.', ',') }}</p>
            <p class="sub">Ocultados desde este panel.</p>
        </article>
        <article class="card">
            <h3>Con sugerencia</h3>
            <p class="value">{{ number_format((int) $metricas['con_sugerencia'], 0, '.', ',') }}</p>
            <p class="sub">Solo visible para administradores.</p>
        </article>
    </section>

    <section class="panel">
        <div class="panel-head">
            <h2>Moderación de comentarios y sugerencias</h2>
            <span>{{ $comentariosPortal->total() }} registro(s)</span>
        </div>
        <p>Puedes ocultar comentarios en la sección pública o eliminarlos definitivamente. Las sugerencias se leen únicamente aquí.</p>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Puntaje</th>
                        <th>Comentario público</th>
                        <th>Sugerencia privada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comentariosPortal as $item)
                        <tr>
                            <td>
                                <span class="feedback-user">
                                    {{ trim(($item->usuario?->name ?? 'Usuario').' '.($item->usuario?->apellidos ?? '')) }}
                                    <small>{{ $item->usuario?->email ?? 'Sin correo' }}</small>
                                    <small class="date">{{ optional($item->created_at)->format('Y-m-d H:i') }}</small>
                                </span>
                            </td>
                            <td>
                                <span class="stars" aria-label="Puntaje {{ $item->puntaje }} de 5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $item->puntaje ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                                    @endfor
                                </span>
                            </td>
                            <td><p class="text">{{ $item->comentario }}</p></td>
                            <td>
                                @if (!empty($item->sugerencia))
                                    <div class="sugerencia">{{ $item->sugerencia }}</div>
                                @else
                                    <div class="sugerencia empty">Sin sugerencia</div>
                                @endif
                            </td>
                            <td>
                                <span class="tag {{ $item->visible ? 'visible' : 'hidden' }}">
                                    {{ $item->visible ? 'Visible' : 'Oculto' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <form method="POST" action="{{ route('admin.PanelAdministrativo.sugerencias.visibilidad.update', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="visible" value="{{ $item->visible ? '0' : '1' }}">
                                        <button type="submit" class="btn {{ $item->visible ? 'btn-warn' : 'btn-main' }}">
                                            {{ $item->visible ? 'Ocultar' : 'Mostrar' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.PanelAdministrativo.sugerencias.destroy', $item) }}" onsubmit="return confirm('Se eliminará este comentario. ¿Deseas continuar?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Aún no hay reseñas o sugerencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:12px;">
            {{ $comentariosPortal->links() }}
        </div>
    </section>
@endsection

