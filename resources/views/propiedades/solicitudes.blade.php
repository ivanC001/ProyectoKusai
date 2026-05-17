@extends('layouts.client')

@section('title', 'Solicitudes recibidas | Kusay.pe')

@section('styles')
<style>
    .requests-wrap {
        width: min(1180px, 94vw);
        margin: 0 auto;
    }
    .requests-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .requests-title {
        margin: 0;
        font-family: "Fraunces", serif;
        font-size: clamp(1.8rem, 3.8vw, 2.5rem);
        color: #164634;
    }
    .requests-subtitle {
        margin: 6px 0 0;
        color: #678577;
    }
    .btn-main,
    .btn-soft,
    .btn-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid transparent;
        padding: 10px 14px;
        font-weight: 800;
        text-decoration: none;
    }
    .btn-main {
        color: #fff;
        background: linear-gradient(130deg, #2d8f5f, #17573c);
    }
    .btn-soft,
    .btn-link {
        color: #315d4b;
        border-color: #c6d5cc;
        background: #f8fbf9;
    }
    .stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
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
        grid-template-columns: 1fr auto;
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
    .btn-filter {
        border: none;
        border-radius: 10px;
        padding: 10px 12px;
        color: #fff;
        background: #194f38;
        font: inherit;
        font-weight: 800;
        cursor: pointer;
    }
    .requests-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    .request-card {
        border-radius: 14px;
        border: 1px solid #d4e1d9;
        background: #fff;
        padding: 14px;
        box-shadow: 0 8px 20px rgba(17, 58, 40, .08);
    }
    .request-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .request-name {
        margin: 0;
        color: #194634;
        font-size: 1.04rem;
        font-weight: 900;
    }
    .request-date {
        color: #6b8a7d;
        font-size: .82rem;
        font-weight: 800;
        white-space: nowrap;
    }
    .property-line {
        margin: 0 0 10px;
        color: #607f71;
        font-size: .9rem;
        font-weight: 800;
    }
    .contact-lines {
        display: grid;
        gap: 8px;
        margin-bottom: 12px;
    }
    .contact-line {
        border-radius: 10px;
        border: 1px solid #d7e4dd;
        background: #f8fbf9;
        padding: 9px 10px;
    }
    .contact-line span {
        display: block;
        color: #6b8a7d;
        font-size: .72rem;
        font-weight: 900;
        letter-spacing: .05em;
        text-transform: uppercase;
    }
    .contact-line strong {
        display: block;
        margin-top: 3px;
        color: #244b3b;
        overflow-wrap: anywhere;
    }
    .message {
        margin: 0 0 12px;
        color: #355b4b;
        line-height: 1.55;
        white-space: pre-line;
    }
    .request-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
    @media (max-width: 900px) {
        .requests-grid {
            grid-template-columns: 1fr;
        }
        .filters {
            grid-template-columns: 1fr;
        }
        .filters-actions {
            align-items: stretch;
            flex-wrap: wrap;
        }
    }
    @media (max-width: 700px) {
        .stats {
            grid-template-columns: 1fr;
        }
        .request-top {
            display: grid;
        }
        .request-date {
            white-space: normal;
        }
        .request-actions > * {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
    <div class="requests-wrap">
        <header class="requests-head">
            <div>
                <h1 class="requests-title">Solicitudes recibidas</h1>
                <p class="requests-subtitle">Revisa los compradores interesados y responde desde tus datos de contacto.</p>
            </div>
            <a href="{{ route('propiedades.mine') }}" class="btn-soft">Volver a mis publicaciones</a>
        </header>

        <section class="stats">
            <article class="stat-card">
                <p class="stat-label">Total</p>
                <p class="stat-value">{{ $estadisticas['total'] }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Propiedades con solicitudes</p>
                <p class="stat-value">{{ $estadisticas['propiedades_con_solicitudes'] }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Ultimos 7 dias</p>
                <p class="stat-value">{{ $estadisticas['ultimos_7_dias'] }}</p>
            </article>
        </section>

        <form class="filters" method="GET" action="{{ route('propiedades.solicitudes') }}">
            <div>
                <label for="propiedad_id">PROPIEDAD</label>
                <select id="propiedad_id" name="propiedad_id">
                    <option value="">Todas mis propiedades</option>
                    @foreach ($propiedadesUsuario as $propiedad)
                        <option value="{{ $propiedad->id }}" @selected((string) $filtros['propiedad_id'] === (string) $propiedad->id)>
                            {{ $propiedad->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filters-actions">
                <button class="btn-filter" type="submit">Aplicar</button>
                <a class="btn-link" href="{{ route('propiedades.solicitudes') }}">Limpiar</a>
            </div>
        </form>

        @if ($solicitudes->isEmpty())
            <section class="empty">
                <h3>Aun no recibiste solicitudes</h3>
                <p>Cuando un comprador envie el formulario desde una publicacion, aparecera aqui.</p>
                <a href="{{ route('propiedades.mine') }}" class="btn-main">Ver mis publicaciones</a>
            </section>
        @else
            <section class="requests-grid">
                @foreach ($solicitudes as $solicitud)
                    @php
                        $subject = rawurlencode('Consulta sobre '.$solicitud->propiedad?->titulo);
                        $body = rawurlencode("Hola {$solicitud->nombre}, gracias por tu interes en {$solicitud->propiedad?->titulo}.");
                        $mailto = 'mailto:'.$solicitud->email.'?subject='.$subject.'&body='.$body;
                        $telefonoHref = $solicitud->telefono ? 'tel:'.preg_replace('/\D+/', '', $solicitud->telefono) : null;
                    @endphp

                    <article class="request-card">
                        <div class="request-top">
                            <div>
                                <h2 class="request-name">{{ $solicitud->nombre }}</h2>
                                <p class="property-line">
                                    {{ $solicitud->propiedad?->titulo ?? 'Propiedad no disponible' }}
                                    @if ($solicitud->propiedad?->ubicacion)
                                        · {{ $solicitud->propiedad->ubicacion->distrito }}, {{ $solicitud->propiedad->ubicacion->departamento }}
                                    @endif
                                </p>
                            </div>
                            <span class="request-date">{{ optional($solicitud->created_at)->format('Y-m-d H:i') }}</span>
                        </div>

                        <div class="contact-lines">
                            <div class="contact-line">
                                <span>Correo</span>
                                <strong>{{ $solicitud->email }}</strong>
                            </div>
                            <div class="contact-line">
                                <span>Telefono</span>
                                <strong>{{ $solicitud->telefono ?: 'No indicado' }}</strong>
                            </div>
                        </div>

                        <p class="message">{{ $solicitud->mensaje }}</p>

                        <div class="request-actions">
                            <a class="btn-main" href="{{ $mailto }}">Responder correo</a>
                            @if ($telefonoHref)
                                <a class="btn-link" href="{{ $telefonoHref }}">Llamar</a>
                            @endif
                            @if ($solicitud->propiedad)
                                <a class="btn-link" href="{{ route('propiedades.detalle', $solicitud->propiedad) }}">Ver publicacion</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </section>

            @if ($solicitudes->lastPage() > 1)
                <nav class="pager" aria-label="Paginacion de solicitudes">
                    @if ($solicitudes->onFirstPage())
                        <span>&lt;</span>
                    @else
                        <a href="{{ $solicitudes->previousPageUrl() }}">&lt;</a>
                    @endif

                    @for ($page = 1; $page <= $solicitudes->lastPage(); $page++)
                        @if ($page === $solicitudes->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $solicitudes->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($solicitudes->hasMorePages())
                        <a href="{{ $solicitudes->nextPageUrl() }}">&gt;</a>
                    @else
                        <span>&gt;</span>
                    @endif
                </nav>
            @endif
        @endif
    </div>
@endsection
