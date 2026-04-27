@extends('layouts.client')

@section('title', 'Editar publicacion | Kusay.pe')

@section('styles')
<style>
    .container {
        width: min(1080px, 94vw);
        margin: 0 auto;
    }
    .page-head {
        display: flex;
        align-items: end;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .crumb {
        color: #6d8a7e;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
    }
    h1 {
        margin: 6px 0;
        font-family: "Fraunces", serif;
        font-size: clamp(28px, 3vw, 38px);
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
    .cover-preview {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 14px;
        align-items: center;
        border: 1px solid #c8ddd0;
        background: #f4faf7;
        border-radius: 12px;
        padding: 10px;
    }
    .cover-preview img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #c7d8cf;
        background: #dce9e2;
    }
    .cover-empty {
        width: 100%;
        height: 170px;
        display: grid;
        place-items: center;
        border-radius: 10px;
        border: 1px dashed #b9cdc2;
        color: #56796a;
        font-size: .92rem;
        font-weight: 700;
        background: #edf6f1;
    }
    .cover-help h3 {
        margin: 0 0 6px;
        color: #1f543f;
        font-size: 1.1rem;
    }
    .cover-help p {
        margin: 0;
        color: #5f7f71;
    }
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
    }
    .photo-item {
        border: 1px solid #cfe0d7;
        border-radius: 12px;
        overflow: hidden;
        background: #f6faf8;
    }
    .photo-item img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-bottom: 1px solid #cfe0d7;
        background: #dce9e2;
    }
    .photo-item-footer {
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: .8rem;
        color: #355b4b;
    }
    .photo-check {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .78rem;
        font-weight: 700;
        color: #385f4f;
    }
    .badge {
        border-radius: 999px;
        border: 1px solid #bcd2c6;
        padding: 2px 8px;
        background: #eff6f2;
        font-size: .72rem;
        color: #426a59;
        font-weight: 800;
    }
    .photo-uploader {
        border: 1px dashed #b8cec1;
        border-radius: 12px;
        background: #f6fbf8;
        padding: 10px;
    }
    .upload-note {
        margin-top: 6px;
        font-size: .84rem;
        color: #5a7a6c;
    }
    .new-photo-preview-grid {
        margin-top: 10px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 8px;
    }
    .new-photo-preview-grid img {
        width: 100%;
        height: 90px;
        object-fit: cover;
        border-radius: 9px;
        border: 1px solid #c5d7ce;
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
    textarea {
        min-height: 130px;
        resize: vertical;
    }
    .error-text {
        color: #a72828;
        font-size: 12px;
        font-weight: 700;
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
    @media (max-width: 900px) {
        .cover-preview {
            grid-template-columns: 1fr;
        }
        .span-8, .span-4, .span-3, .span-2 {
            grid-column: span 12;
        }
        .panel {
            padding: 16px;
        }
    }
</style>
@endsection

@section('content')
    <div class="container">
        <header class="page-head">
            <div>
                <a href="{{ route('propiedades.mine') }}" class="crumb">Volver a mis publicaciones</a>
                <h1>Editar publicacion</h1>
                <p class="subtitle">Actualiza datos y gestiona fotos de tu publicacion en un solo lugar.</p>
            </div>
        </header>

        @if ($errors->any())
            <div class="alert error">Hay errores en el formulario. Revisa los campos marcados.</div>
        @endif

        <section class="panel">
            <h2 class="section-title">Foto principal</h2>
            <p class="section-help">La portada es la primera foto registrada despues de guardar cambios.</p>
            <div class="cover-preview">
                @if ($propiedad->portadaImagen)
                    <img src="{{ route('propiedades.imagen.show', [$propiedad, $propiedad->portadaImagen]) }}" alt="Portada de {{ $propiedad->titulo }}">
                @else
                    <div class="cover-empty">Sin foto de portada</div>
                @endif
                <div class="cover-help">
                    <h3>{{ $propiedad->imagenes_count }} foto(s) registrada(s)</h3>
                    <p>Desde esta pantalla puedes quitar fotos actuales y agregar nuevas.</p>
                </div>
            </div>
        </section>

        <form class="panel" action="{{ route('propiedades.update', $propiedad) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <h2 class="section-title">Gestion de fotos</h2>
            <p class="section-help">Marca las fotos que quieres quitar y/o agrega nuevas imagenes.</p>

            @php
                $imagenesMarcadas = collect(old('remover_imagenes', []))
                    ->map(static fn ($id) => (string) $id)
                    ->all();
            @endphp

            @if ($propiedad->imagenes->isNotEmpty())
                <div class="photo-grid">
                    @foreach ($propiedad->imagenes as $imagen)
                        <article class="photo-item">
                            <img src="{{ route('propiedades.imagen.show', [$propiedad, $imagen]) }}" alt="Foto {{ $loop->iteration }}">
                            <div class="photo-item-footer">
                                <span class="badge">{{ $loop->first ? 'Portada' : 'Foto '.$loop->iteration }}</span>
                                <label class="photo-check">
                                    <input
                                        type="checkbox"
                                        name="remover_imagenes[]"
                                        value="{{ $imagen->id }}"
                                        @checked(in_array((string) $imagen->id, $imagenesMarcadas, true))
                                    >
                                    Quitar
                                </label>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            @error('remover_imagenes') <span class="error-text">{{ $message }}</span> @enderror
            @error('remover_imagenes.*') <span class="error-text">{{ $message }}</span> @enderror

            <div class="field span-12" style="margin-top:12px;">
                <label for="nuevas_fotos">AGREGAR NUEVAS FOTOS</label>
                <div class="photo-uploader">
                    <input id="nuevas_fotos" name="nuevas_fotos[]" type="file" accept=".jpg,.jpeg,.png,.webp" multiple>
                    <p class="upload-note">Puedes subir varias fotos a la vez. Maximo total por publicacion: 12 fotos.</p>
                    <div id="nuevas-fotos-preview" class="new-photo-preview-grid" aria-live="polite"></div>
                </div>
                @error('nuevas_fotos') <span class="error-text">{{ $message }}</span> @enderror
                @error('nuevas_fotos.*') <span class="error-text">{{ $message }}</span> @enderror
            </div>

            <hr class="divider">

            <h2 class="section-title">Datos principales</h2>
            <p class="section-help">Edita la informacion que veran tus compradores.</p>

            <div class="grid">
                <div class="field span-8">
                    <label for="titulo">TITULO</label>
                    <input id="titulo" name="titulo" type="text" value="{{ old('titulo', $propiedad->titulo) }}" placeholder="Ej: Casa amplia en zona centrica">
                    @error('titulo') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="tipo_propiedad_id">TIPO DE PROPIEDAD</label>
                    <select id="tipo_propiedad_id" name="tipo_propiedad_id">
                        <option value="">Selecciona...</option>
                        @foreach ($tiposPropiedad as $tipo)
                            <option value="{{ $tipo->id }}" @selected((string) old('tipo_propiedad_id', $propiedad->tipo_propiedad_id) === (string) $tipo->id)>{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_propiedad_id') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-12">
                    <label for="descripcion">DESCRIPCION</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Describe ventajas, acabados, entorno, servicios cercanos...">{{ old('descripcion', $propiedad->descripcion) }}</textarea>
                    @error('descripcion') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-3">
                    <label for="tipo">OPERACION</label>
                    <select id="tipo" name="tipo">
                        <option value="venta" @selected(old('tipo', $propiedad->tipo) === 'venta')>Venta</option>
                        <option value="alquiler" @selected(old('tipo', $propiedad->tipo) === 'alquiler')>Alquiler</option>
                    </select>
                    @error('tipo') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-3">
                    <label for="estado">ESTADO</label>
                    <select id="estado" name="estado">
                        <option value="disponible" @selected(old('estado', $propiedad->estado) === 'disponible')>Disponible</option>
                        <option value="reservado" @selected(old('estado', $propiedad->estado) === 'reservado')>Reservado</option>
                        <option value="vendido" @selected(old('estado', $propiedad->estado) === 'vendido')>Vendido</option>
                    </select>
                    @error('estado') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-3">
                    <label for="precio">PRECIO (S/.)</label>
                    <input id="precio" name="precio" type="number" step="0.01" min="0" value="{{ old('precio', $propiedad->precio) }}" placeholder="125000">
                    @error('precio') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-3">
                    <label for="area">AREA (M2)</label>
                    <input id="area" name="area" type="number" step="0.01" min="0" value="{{ old('area', $propiedad->area) }}" placeholder="120">
                    @error('area') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="habitaciones">DORMITORIOS</label>
                    <input id="habitaciones" name="habitaciones" type="number" min="0" value="{{ old('habitaciones', $propiedad->habitaciones) }}" placeholder="3">
                    @error('habitaciones') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="banos">BANOS</label>
                    <input id="banos" name="banos" type="number" min="0" value="{{ old('banos', $propiedad->banos) }}" placeholder="2">
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
                    <select
                        id="departamento"
                        name="departamento"
                        required
                        data-old="{{ old('departamento', $propiedad->ubicacion?->departamento) }}"
                    >
                        <option value="">Selecciona una region...</option>
                        @foreach ($ubicacionesPeru as $departamentoData)
                            <option
                                value="{{ $departamentoData['departamento'] }}"
                                @selected(old('departamento', $propiedad->ubicacion?->departamento) === $departamentoData['departamento'])
                            >
                                {{ $departamentoData['departamento'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('departamento') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="provincia">PROVINCIA</label>
                    <select
                        id="provincia"
                        name="provincia"
                        required
                        data-old="{{ old('provincia', $propiedad->ubicacion?->provincia) }}"
                    >
                        <option value="">Selecciona una provincia...</option>
                    </select>
                    @error('provincia') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-4">
                    <label for="distrito">DISTRITO</label>
                    <select
                        id="distrito"
                        name="distrito"
                        required
                        data-old="{{ old('distrito', $propiedad->ubicacion?->distrito) }}"
                    >
                        <option value="">Selecciona un distrito...</option>
                    </select>
                    @error('distrito') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-8">
                    <label for="direccion">DIRECCION REFERENCIAL</label>
                    <input id="direccion" name="direccion" type="text" value="{{ old('direccion', $propiedad->direccion) }}" placeholder="Av. Principal 123, cerca a plaza...">
                    @error('direccion') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="latitud">LATITUD</label>
                    <input id="latitud" name="latitud" type="number" step="0.0000001" value="{{ old('latitud', $propiedad->latitud) }}" placeholder="-8.3800000">
                    @error('latitud') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field span-2">
                    <label for="longitud">LONGITUD</label>
                    <input id="longitud" name="longitud" type="number" step="0.0000001" value="{{ old('longitud', $propiedad->longitud) }}" placeholder="-74.5400000">
                    @error('longitud') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="actions">
                <a class="btn btn-link" href="{{ route('propiedades.mine') }}">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        (() => {
            const inputFotos = document.getElementById('nuevas_fotos');
            const previewContainer = document.getElementById('nuevas-fotos-preview');

            if (!inputFotos || !previewContainer) {
                return;
            }

            inputFotos.addEventListener('change', () => {
                previewContainer.innerHTML = '';

                const files = Array.from(inputFotos.files || []);
                files.forEach((file) => {
                    if (!file.type.startsWith('image/')) {
                        return;
                    }

                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    image.alt = `Nueva foto ${file.name}`;
                    image.onload = () => URL.revokeObjectURL(image.src);
                    previewContainer.appendChild(image);
                });
            });
        })();

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
