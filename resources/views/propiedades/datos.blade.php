@extends('layouts.client')

@section('title', 'Paso 3: Completa Datos | Kusay.pe')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<style>
    .container {
        width: min(1080px, 94vw);
        margin: 0 auto;
    }
    .steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 18px;
    }
    .step-card {
        background: #f4f6f5;
        border: 1px solid #cfddd4;
        border-radius: 16px;
        padding: 18px;
        min-height: 120px;
    }
    .step-card.done {
        background: #eef8f2;
        border-color: #9ec7b1;
    }
    .step-card.current {
        background: #e6f5ec;
        border-color: #4f9d77;
        box-shadow: 0 10px 22px rgba(17, 67, 46, .1);
    }
    .step-num {
        color: #0f4d35;
        font-weight: 800;
        font-size: 1.6rem;
        margin-bottom: 6px;
    }
    .step-title {
        font-family: "Fraunces", serif;
        font-size: 1rem;
        margin: 0 0 5px;
    }
    .step-text {
        color: #5f7e70;
        font-size: .9rem;
        margin: 0;
    }
    .page-head {
        padding: 8px 0 14px;
    }
    .crumb {
        color: #6d8a7e;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
    }
    h1 {
        margin: 10px 0 6px;
        font-family: "Fraunces", serif;
        font-size: clamp(28px, 3vw, 40px);
        letter-spacing: -0.5px;
    }
    .subtitle {
        margin: 0;
        color: #648477;
        font-size: 16px;
    }
    .alert {
        border-radius: 12px;
        padding: 12px 14px;
        margin: 12px 0;
        border: 1px solid transparent;
        font-weight: 600;
    }
    .alert.error {
        background: #fce8e8;
        border-color: #f4c2c2;
        color: #982828;
    }
    .alert.warn {
        background: #fff8e8;
        border-color: #f0dfb3;
        color: #704f00;
    }
    .panel {
        background: #fff;
        border: 1px solid #d8e2dc;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 20px 34px rgba(8, 44, 30, 0.08);
        margin-bottom: 18px;
    }
    .section-title {
        margin: 0 0 4px;
        font-size: 21px;
        font-weight: 800;
        color: #113f2f;
    }
    .section-help {
        margin: 0 0 16px;
        color: #648477;
        font-size: 14px;
    }
    .photo-summary {
        border: 1px solid #c8ddd0;
        background: #f4faf7;
        border-radius: 12px;
        padding: 10px 12px;
        margin-bottom: 14px;
    }
    .photo-summary strong {
        color: #1f543f;
    }
    .photos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px;
    }
    .photos img {
        width: 100%;
        height: 84px;
        object-fit: cover;
        border-radius: 9px;
        border: 1px solid #d0ddd6;
        background: #dce9e2;
    }
    .grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 12px;
        margin-bottom: 18px;
    }
    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .span-12 { grid-column: span 12; }
    .span-8 { grid-column: span 8; }
    .span-6 { grid-column: span 6; }
    .span-4 { grid-column: span 4; }
    .span-3 { grid-column: span 3; }
    .span-2 { grid-column: span 2; }
    label {
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .05em;
        color: #557568;
    }
    input, select, textarea {
        width: 100%;
        border: 1px solid #cedad4;
        border-radius: 10px;
        background: #f6faf8;
        color: #194332;
        padding: 11px 12px;
        font-size: 15px;
        outline: none;
        font: inherit;
    }
    input[readonly] {
        background: #edf4f0;
        color: #4f7463;
    }
    textarea {
        min-height: 130px;
        resize: vertical;
    }
    .error-text {
        color: #a72828;
        font-size: 12px;
        font-weight: 700;
    }
    .map-wrapper {
        border: 1px solid #c8ddd0;
        border-radius: 14px;
        background: #f4faf7;
        padding: 12px;
    }
    .map-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 9px;
    }
    .map-head p {
        margin: 0;
        color: #4f7463;
        font-size: .88rem;
        font-weight: 700;
    }
    #mapa-propiedad {
        width: 100%;
        height: 340px;
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
    .coord-chip {
        border-radius: 999px;
        border: 1px solid #c8ddd1;
        background: #eef6f1;
        color: #2f5d4b;
        font-size: .78rem;
        font-weight: 800;
        padding: 5px 10px;
    }
    .map-help {
        margin-top: 8px;
        color: #648477;
        font-size: .83rem;
    }
    .divider {
        border: none;
        border-top: 1px dashed #d6e1db;
        margin: 8px 0 16px;
    }
    .actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn {
        border: 1px solid transparent;
        border-radius: 11px;
        padding: 11px 18px;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(160deg, #186245, #124f37);
        color: #fff;
        min-width: 180px;
    }
    .btn-link {
        border-color: #c8d5ce;
        color: #2d5a49;
        background: #f8fbf9;
    }
    .project-hint {
        grid-column: span 12;
        border: 1px solid #c7d9cf;
        border-radius: 12px;
        background: #f4faf7;
        color: #2f5b49;
        padding: 10px 12px;
        font-size: .86rem;
        line-height: 1.45;
        display: none;
    }
    .project-hint.active {
        display: block;
    }
    .project-hint strong {
        color: #18553b;
    }
    .project-hint-list {
        margin: 8px 0 0;
        padding-left: 18px;
    }
    .project-hint-list li {
        margin: 3px 0;
    }
    @media (max-width: 900px) {
        .span-8, .span-6, .span-4, .span-3, .span-2 {
            grid-column: span 12;
        }
        .steps {
            grid-template-columns: 1fr;
        }
        .panel {
            padding: 16px;
        }
    }
</style>
@endsection

@section('content')
    <div class="container">
        <section class="steps">
            <article class="step-card done">
                <div class="step-num">1.</div>
                <h3 class="step-title">Crea tu cuenta</h3>
                <p class="step-text">Registro rapido con correo y telefono.</p>
            </article>
            <article class="step-card done">
                <div class="step-num">2.</div>
                <h3 class="step-title">Sube fotos</h3>
                <p class="step-text">Imagenes cargadas para atraer mas interesados.</p>
            </article>
            <article class="step-card current">
                <div class="step-num">3.</div>
                <h3 class="step-title">Completa datos</h3>
                <p class="step-text">Precio, metraje, ubicacion y caracteristicas clave.</p>
            </article>
            <article class="step-card">
                <div class="step-num">4.</div>
                <h3 class="step-title">Publica y recibe contactos</h3>
                <p class="step-text">Tu aviso queda listo y recibe mensajes de compradores.</p>
            </article>
        </section>

        <header class="page-head">
            <a href="{{ route('propiedades.create') }}" class="crumb">Volver a paso 2 (fotos)</a>
            <h1>Paso 3: Completa datos de la propiedad</h1>
            <p class="subtitle">Ingresa la informacion principal y finaliza tu publicacion.</p>
        </header>

        @if ($errors->any())
            <div class="alert error">Hay errores en el formulario. Revisa los campos marcados.</div>
        @endif

        @if (!$puedeGuardar)
            <div class="alert warn">Necesitas al menos una foto cargada y un tipo de propiedad para continuar.</div>
        @endif

        <section class="panel">
            <h2 class="section-title">Resumen de fotos</h2>
            <p class="section-help">Las fotos se registraron por separado y se publicaran junto con estos datos.</p>
            <div class="photo-summary">
                Tienes <strong>{{ $fotosCount }}</strong> foto(s) lista(s) para publicar.
            </div>

            @if ($fotosCount > 0)
                <div class="photos">
                    @foreach ($fotosTemporales as $index => $path)
                        <img src="{{ route('propiedades.fotos.preview', $index) }}" alt="Foto {{ $index + 1 }}">
                    @endforeach
                </div>
            @endif
        </section>

        <form class="panel" action="{{ route('propiedades.store') }}" method="POST">
            @csrf

            <h2 class="section-title">Datos principales</h2>
            <p class="section-help">Informacion base que vera el comprador.</p>

            <div class="grid">
                <div class="field span-8">
                    <label for="titulo">TITULO</label>
                    <input id="titulo" name="titulo" type="text" value="{{ old('titulo') }}" placeholder="Ej: Casa amplia en zona centrica">
                    @error('titulo') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="tipo_propiedad_id">TIPO DE PROPIEDAD</label>
                    <select id="tipo_propiedad_id" name="tipo_propiedad_id">
                        <option value="">Selecciona...</option>
                        @foreach ($tiposPropiedad as $tipo)
                            <option value="{{ $tipo->id }}" @selected(old('tipo_propiedad_id') == $tipo->id)>{{ $tipo->nombre }}@if(str_contains(mb_strtolower($tipo->nombre), 'proyecto')) (proyecto)@endif</option>
                        @endforeach
                    </select>
                    @error('tipo_propiedad_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-12">
                    <label for="descripcion">DESCRIPCION</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe ventajas, acabados, entorno, servicios cercanos...">{{ old('descripcion') }}</textarea>
                    @error('descripcion') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-3">
                    <label for="tipo">OPERACION</label>
                    <select id="tipo" name="tipo">
                        <option value="venta" @selected(old('tipo', 'venta') === 'venta')>Venta</option>
                        <option value="alquiler" @selected(old('tipo') === 'alquiler')>Alquiler</option>
                    </select>
                    @error('tipo') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="project-hint" id="project-hint">
                    <strong>Registro de proyecto inmobiliario:</strong> esta publicacion se mostrara en la seccion Proyectos.
                    <ul class="project-hint-list">
                        <li>Operacion obligatoria: <strong>Venta</strong>.</li>
                        <li>En el titulo usa el nombre del proyecto y zona. Ejemplo: "Condominio Los Cedros - Pucallpa".</li>
                        <li>En la descripcion indica etapas, fecha estimada de entrega, precios desde y metrajes.</li>
                    </ul>
                </div>

                <div class="field span-3">
                    <label for="estado">ESTADO</label>
                    <select id="estado" name="estado">
                        <option value="disponible" @selected(old('estado', 'disponible') === 'disponible')>Disponible</option>
                        <option value="reservado" @selected(old('estado') === 'reservado')>Reservado</option>
                        <option value="vendido" @selected(old('estado') === 'vendido')>Vendido</option>
                    </select>
                    @error('estado') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="precio">PRECIO (S/.)</label>
                    <input id="precio" name="precio" type="number" step="0.01" min="0" value="{{ old('precio') }}" placeholder="125000">
                    @error('precio') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="precio_usd">PRECIO (US$)</label>
                    <input id="precio_usd" name="precio_usd" type="number" step="0.01" min="0" value="{{ old('precio_usd') }}" placeholder="35000">
                    @error('precio_usd') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="area">AREA (M2)</label>
                    <input id="area" name="area" type="number" step="0.01" min="0" value="{{ old('area') }}" placeholder="120">
                    @error('area') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="habitaciones">DORMITORIOS</label>
                    <input id="habitaciones" name="habitaciones" type="number" min="0" value="{{ old('habitaciones') }}" placeholder="3">
                    @error('habitaciones') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="banos">BANOS</label>
                    <input id="banos" name="banos" type="number" min="0" value="{{ old('banos') }}" placeholder="2">
                    @error('banos') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label>PUBLICADO POR</label>
                    <input type="text" readonly value="{{ auth()->user()->name }} ({{ auth()->user()->email }})">
                </div>
            </div>

            <hr class="divider">

            <h2 class="section-title">Ubicacion</h2>
            <p class="section-help">Selecciona region, provincia y distrito desde el padron oficial del Peru.</p>

            <div class="grid">
                <div class="field span-4">
                    <label for="departamento">REGION (DEPARTAMENTO)</label>
                    <select id="departamento" name="departamento" required data-old="{{ old('departamento') }}">
                        <option value="">Selecciona una region...</option>
                        @foreach ($ubicacionesPeru as $departamentoData)
                            <option value="{{ $departamentoData['departamento'] }}" @selected(old('departamento') === $departamentoData['departamento'])>
                                {{ $departamentoData['departamento'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="provincia">PROVINCIA</label>
                    <select id="provincia" name="provincia" required data-old="{{ old('provincia') }}">
                        <option value="">Selecciona una provincia...</option>
                    </select>
                    @error('provincia') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="distrito">DISTRITO</label>
                    <select id="distrito" name="distrito" required data-old="{{ old('distrito') }}">
                        <option value="">Selecciona un distrito...</option>
                    </select>
                    @error('distrito') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-6">
                    <label for="direccion">DIRECCION COMPLETA</label>
                    <input id="direccion" name="direccion" type="text" value="{{ old('direccion') }}" placeholder="Av. Principal 123, Urbanizacion, Calle, Nro.">
                    @error('direccion') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-6">
                    <label for="referencia">REFERENCIA</label>
                    <input id="referencia" name="referencia" type="text" value="{{ old('referencia') }}" placeholder="Frente al parque, cerca al mercado, etc.">
                    @error('referencia') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label>COORDENADAS GPS</label>
                    <div class="coords">
                        <span class="coord-chip">Lat: <strong id="coord-lat-text">{{ old('latitud', '-') }}</strong></span>
                        <span class="coord-chip">Lng: <strong id="coord-lng-text">{{ old('longitud', '-') }}</strong></span>
                    </div>
                </div>

                <div class="field span-12">
                    <label for="mapa-propiedad">UBICACION EN MAPA (OPENSTREETMAP)</label>
                    <div class="map-wrapper">
                        <div class="map-head">
                            <p>Haz click en el mapa para ubicar tu propiedad. Puedes arrastrar el marcador para ajustar.</p>
                        </div>
                        <div id="mapa-propiedad" aria-label="Mapa para seleccionar ubicacion"></div>
                        <p class="map-help" id="mapa-direccion-ayuda">Al seleccionar un punto, se intentara autocompletar la direccion con OpenStreetMap.</p>
                    </div>
                    <input id="latitud" name="latitud" type="hidden" value="{{ old('latitud') }}">
                    <input id="longitud" name="longitud" type="hidden" value="{{ old('longitud') }}">
                    @error('latitud') <span class="error-text">{{ $message }}</span> @enderror
                    @error('longitud') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="actions">
                <a class="btn btn-link" href="{{ route('propiedades.create') }}">Volver a fotos</a>
                <button class="btn btn-primary" type="submit" @disabled(!$puedeGuardar)>Registrar y publicar</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        (() => {
            const latInput = document.getElementById('latitud');
            const lngInput = document.getElementById('longitud');
            const direccionInput = document.getElementById('direccion');
            const mapContainer = document.getElementById('mapa-propiedad');
            const latText = document.getElementById('coord-lat-text');
            const lngText = document.getElementById('coord-lng-text');
            const direccionAyuda = document.getElementById('mapa-direccion-ayuda');

            if (!latInput || !lngInput || !mapContainer || typeof L === 'undefined') {
                return;
            }

            const peruCenter = [-9.1900, -75.0152];
            const oldLat = Number.parseFloat(latInput.value);
            const oldLng = Number.parseFloat(lngInput.value);
            const hasOldCoords = Number.isFinite(oldLat) && Number.isFinite(oldLng);

            const map = L.map(mapContainer).setView(
                hasOldCoords ? [oldLat, oldLng] : peruCenter,
                hasOldCoords ? 15 : 6
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            let marker = null;
            let reverseAbortController = null;

            const setCoordText = (lat, lng) => {
                if (latText) {
                    latText.textContent = Number.isFinite(lat) ? lat.toFixed(7) : '-';
                }

                if (lngText) {
                    lngText.textContent = Number.isFinite(lng) ? lng.toFixed(7) : '-';
                }
            };

            const reverseGeocode = async (lat, lng) => {
                if (!direccionInput) {
                    return;
                }

                if (reverseAbortController) {
                    reverseAbortController.abort();
                }
                reverseAbortController = new AbortController();

                if (direccionAyuda) {
                    direccionAyuda.textContent = 'Buscando direccion aproximada...';
                }

                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}&accept-language=es`;

                try {
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                        },
                        signal: reverseAbortController.signal,
                    });

                    if (!response.ok) {
                        throw new Error('No se pudo consultar Nominatim.');
                    }

                    const data = await response.json();
                    const direccion = data.display_name ? String(data.display_name).trim() : '';

                    if (direccion !== '') {
                        direccionInput.value = direccion;
                        if (direccionAyuda) {
                            direccionAyuda.textContent = 'Direccion aproximada autocompletada desde OpenStreetMap.';
                        }
                    } else if (direccionAyuda) {
                        direccionAyuda.textContent = 'No se encontro una direccion exacta para este punto.';
                    }
                } catch (error) {
                    if (error.name !== 'AbortError' && direccionAyuda) {
                        direccionAyuda.textContent = 'No se pudo obtener la direccion automaticamente. Puedes ingresarla manualmente.';
                    }
                }
            };

            const updatePosition = (latlng, withReverse = false) => {
                const lat = Number(latlng.lat);
                const lng = Number(latlng.lng);

                latInput.value = lat.toFixed(7);
                lngInput.value = lng.toFixed(7);
                setCoordText(lat, lng);

                if (withReverse) {
                    reverseGeocode(latInput.value, lngInput.value);
                }
            };

            const placeMarker = (latlng, withReverse = false) => {
                // Crea el marcador la primera vez y luego solo actualiza su posicion.
                if (!marker) {
                    marker = L.marker(latlng, { draggable: true }).addTo(map);

                    marker.on('dragend', () => {
                        const current = marker.getLatLng();
                        updatePosition(current, true);
                    });
                } else {
                    marker.setLatLng(latlng);
                }

                updatePosition(latlng, withReverse);
            };

            map.on('click', (event) => {
                placeMarker(event.latlng, true);
            });

            if (hasOldCoords) {
                placeMarker({ lat: oldLat, lng: oldLng }, false);
            } else {
                setCoordText(NaN, NaN);
            }
        })();
    </script>

    <script>
        (() => {
            const tipoPropiedadSelect = document.getElementById('tipo_propiedad_id');
            const operacionSelect = document.getElementById('tipo');
            const hint = document.getElementById('project-hint');

            if (!tipoPropiedadSelect || !operacionSelect || !hint) {
                return;
            }

            const isProyecto = () => {
                const option = tipoPropiedadSelect.options[tipoPropiedadSelect.selectedIndex];
                const nombre = (option?.textContent || '').toLowerCase();
                return nombre.includes('proyecto');
            };

            const refresh = () => {
                const proyecto = isProyecto();
                hint.classList.toggle('active', proyecto);

                if (proyecto && operacionSelect.value !== 'venta') {
                    operacionSelect.value = 'venta';
                }
            };

            tipoPropiedadSelect.addEventListener('change', refresh);
            operacionSelect.addEventListener('change', refresh);
            refresh();
        })();
    </script>

    <script>
        (() => {
            const catalog = @json($ubicacionesPeru, JSON_UNESCAPED_UNICODE);
            const departamentoSelect = document.getElementById('departamento');
            const provinciaSelect = document.getElementById('provincia');
            const distritoSelect = document.getElementById('distrito');

            if (!departamentoSelect || !provinciaSelect || !distritoSelect) {
                return;
            }

            const oldProvincia = provinciaSelect.dataset.old || '';
            const oldDistrito = distritoSelect.dataset.old || '';
            const oldDepartamento = departamentoSelect.dataset.old || '';

            const normalize = (value) => String(value || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim()
                .replace(/\s+/g, ' ')
                .toUpperCase();

            const setOptions = (select, values, placeholder, selectedValue = '') => {
                const normalizedSelected = normalize(selectedValue);
                let selectedFound = false;

                select.innerHTML = '';

                const placeholderOption = document.createElement('option');
                placeholderOption.value = '';
                placeholderOption.textContent = placeholder;
                select.appendChild(placeholderOption);

                values.forEach((value) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;

                    if (normalizedSelected !== '' && normalize(value) === normalizedSelected) {
                        option.selected = true;
                        selectedFound = true;
                    }

                    select.appendChild(option);
                });

                if (!selectedFound) {
                    select.value = '';
                }

                select.disabled = values.length === 0;
            };

            const findDepartamento = (value) => {
                const normalizedValue = normalize(value);

                return catalog.find((item) => normalize(item.departamento) === normalizedValue) || null;
            };

            const findProvincia = (departamentoData, value) => {
                if (!departamentoData) {
                    return null;
                }

                const normalizedValue = normalize(value);

                return departamentoData.provincias.find((item) => normalize(item.provincia) === normalizedValue) || null;
            };

            const refreshProvincias = (selectedProvincia = '') => {
                const departamentoData = findDepartamento(departamentoSelect.value);
                const provincias = departamentoData ? departamentoData.provincias.map((item) => item.provincia) : [];

                setOptions(provinciaSelect, provincias, 'Selecciona una provincia...', selectedProvincia);

                return departamentoData;
            };

            const refreshDistritos = (selectedDistrito = '') => {
                const departamentoData = findDepartamento(departamentoSelect.value);
                const provinciaData = findProvincia(departamentoData, provinciaSelect.value);
                const distritos = provinciaData ? provinciaData.distritos : [];

                setOptions(distritoSelect, distritos, 'Selecciona un distrito...', selectedDistrito);
            };

            departamentoSelect.addEventListener('change', () => {
                refreshProvincias();
                refreshDistritos();
            });

            provinciaSelect.addEventListener('change', () => {
                refreshDistritos();
            });

            if (departamentoSelect.value === '' && oldDepartamento !== '') {
                const normalizedOldDepartamento = normalize(oldDepartamento);
                const matchedOption = Array.from(departamentoSelect.options).find((option) => {
                    return normalize(option.value) === normalizedOldDepartamento;
                });

                if (matchedOption) {
                    departamentoSelect.value = matchedOption.value;
                }
            }

            refreshProvincias(oldProvincia);
            refreshDistritos(oldDistrito);
        })();
    </script>
@endsection
