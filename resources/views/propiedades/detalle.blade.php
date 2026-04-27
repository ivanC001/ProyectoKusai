@extends('layouts.client')

@section('title', 'Detalle de Propiedad | Kusay.pe')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<style>
    .detail-wrap {
        width: min(1080px, 94vw);
        margin: 0 auto;
    }
    .head {
        margin-bottom: 14px;
    }
    .crumb {
        color: #648477;
        text-decoration: none;
        font-weight: 700;
        font-size: .9rem;
    }
    .title {
        margin: 8px 0 6px;
        font-family: "Fraunces", serif;
        color: #153f2f;
        font-size: clamp(1.9rem, 3.4vw, 2.8rem);
        line-height: 1.08;
    }
    .subtitle {
        margin: 0;
        color: #628274;
    }
    .layout {
        display: grid;
        grid-template-columns: 1.45fr 1fr;
        gap: 14px;
    }
    .panel {
        border: 1px solid #d2dfd7;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 28px rgba(9, 42, 29, .08);
        overflow: hidden;
    }
    .cover {
        width: 100%;
        height: 320px;
        object-fit: cover;
        background: #dbe8e1;
    }
    .content {
        padding: 16px;
    }
    .price {
        margin: 0 0 8px;
        color: #b04e27;
        font-family: "Fraunces", serif;
        font-size: clamp(1.4rem, 2.8vw, 2rem);
        line-height: 1;
    }
    .meta {
        margin: 0 0 8px;
        color: #5f7f71;
    }
    .chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 10px;
    }
    .chip {
        border-radius: 999px;
        border: 1px solid #ccd9d2;
        background: #f4f9f6;
        color: #355b4b;
        font-size: .75rem;
        font-weight: 800;
        padding: 4px 8px;
    }
    .desc {
        margin: 0;
        color: #355b4b;
        line-height: 1.64;
        white-space: pre-line;
    }
    .map-panel {
        border: 1px solid #d2dfd7;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 28px rgba(9, 42, 29, .08);
        padding: 14px;
    }
    .map-title {
        margin: 0 0 4px;
        color: #153f2f;
        font-size: 1.12rem;
        font-weight: 800;
    }
    .map-subtitle {
        margin: 0 0 10px;
        color: #648477;
        font-size: .9rem;
    }
    #detalle-mapa {
        width: 100%;
        height: 360px;
        border: 1px solid #bfd6c8;
        border-radius: 12px;
        overflow: hidden;
    }
    .coords {
        margin-top: 8px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .coord {
        border-radius: 999px;
        border: 1px solid #c8ddd1;
        background: #eef6f1;
        color: #2f5d4b;
        font-size: .78rem;
        font-weight: 800;
        padding: 5px 10px;
    }
    @media (max-width: 900px) {
        .layout {
            grid-template-columns: 1fr;
        }
        .cover {
            height: 250px;
        }
        #detalle-mapa {
            height: 280px;
        }
    }
</style>
@endsection

@section('content')
    <div class="detail-wrap">
        <header class="head">
            <a class="crumb" href="{{ route('propiedades.mine') }}">Volver a mis publicaciones</a>
            <h1 class="title">{{ $propiedad->titulo }}</h1>
            <p class="subtitle">{{ $propiedad->direccion }}</p>
        </header>

        <div class="layout">
            <article class="panel">
                @if ($propiedad->portadaImagen)
                    <img
                        class="cover"
                        src="{{ route('propiedades.imagen.show', [$propiedad, $propiedad->portadaImagen]) }}"
                        alt="Portada de {{ $propiedad->titulo }}"
                    >
                @else
                    <div class="cover"></div>
                @endif

                <div class="content">
                    <p class="price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                    <p class="meta">
                        {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                        {{ $propiedad->ubicacion?->provincia ?? 'Sin provincia' }},
                        {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                    </p>

                    <div class="chips">
                        <span class="chip">{{ ucfirst($propiedad->tipo) }}</span>
                        <span class="chip">{{ ucfirst($propiedad->estado) }}</span>
                        <span class="chip">{{ $propiedad->tipoPropiedad?->nombre ?? 'Propiedad' }}</span>
                        <span class="chip">{{ $propiedad->imagenes_count }} foto(s)</span>
                        <span class="chip">{{ $propiedad->contactos_count }} contacto(s)</span>
                        <span class="chip">{{ $propiedad->visitas_count }} clic(s)</span>
                        <span class="chip">{{ $propiedad->favoritos_count }} favorito(s)</span>
                    </div>

                    <p class="desc">{{ $propiedad->descripcion }}</p>
                </div>
            </article>

            <aside class="map-panel">
                <h2 class="map-title">Ubicacion registrada</h2>
                <p class="map-subtitle">Mapa basado en OpenStreetMap con marcador fijo en la coordenada guardada.</p>
                <div
                    id="detalle-mapa"
                    data-lat="{{ $propiedad->latitud }}"
                    data-lng="{{ $propiedad->longitud }}"
                ></div>
                <div class="coords">
                    <span class="coord">Lat: {{ number_format((float) $propiedad->latitud, 7, '.', '') }}</span>
                    <span class="coord">Lng: {{ number_format((float) $propiedad->longitud, 7, '.', '') }}</span>
                </div>
            </aside>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    (() => {
        const mapElement = document.getElementById('detalle-mapa');
        if (!mapElement || typeof L === 'undefined') {
            return;
        }

        const lat = Number.parseFloat(mapElement.dataset.lat || '');
        const lng = Number.parseFloat(mapElement.dataset.lng || '');
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
            return;
        }

        const map = L.map(mapElement, {
            zoomControl: true,
            scrollWheelZoom: false,
        }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        L.marker([lat, lng]).addTo(map);
    })();
</script>
@endsection
