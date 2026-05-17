@extends('layouts.client')

@section('title', 'Paso 2: Sube Fotos | Kusay.pe')

@section('styles')
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
    .alert.success {
        background: #d6f4e3;
        border-color: #a7d8bf;
        color: #0c5a3a;
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

    .uploader {
        border: 2px dashed #9fc5b1;
        background: #f2faf5;
        border-radius: 14px;
        padding: 16px;
        transition: .2s ease;
    }
    .native-file-input {
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }
    .uploader.dragging {
        border-color: #4f9d77;
        background: #e9f7ef;
        box-shadow: inset 0 0 0 2px rgba(79, 157, 119, .2);
    }
    .uploader-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .uploader-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #1b4a37;
    }
    .uploader-sub {
        margin: 3px 0 0;
        color: #5f7f71;
        font-size: .87rem;
    }
    .uploader-btn {
        border: 1px solid #8fbca6;
        background: #fff;
        color: #1f5b43;
        border-radius: 10px;
        padding: 9px 13px;
        font: inherit;
        font-size: .9rem;
        font-weight: 800;
        cursor: pointer;
    }
    .uploader-btn:hover {
        background: #f4faf7;
    }
    .uploader-counter {
        margin-top: 9px;
        color: #315f4c;
        font-size: .84rem;
        font-weight: 700;
    }

    .preview-grid,
    .saved-grid {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 10px;
    }
    .preview-item {
        border: 1px solid #cad9d1;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }
    .preview-image {
        width: 100%;
        height: 94px;
        object-fit: cover;
        display: block;
        background: #dce9e2;
    }
    .preview-meta {
        padding: 7px;
    }
    .preview-name {
        color: #274d3d;
        font-size: .76rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }
    .preview-remove {
        width: 100%;
        border: 1px solid #e4c5c5;
        border-radius: 8px;
        background: #fff4f4;
        color: #983131;
        font-size: .74rem;
        font-weight: 800;
        padding: 5px 6px;
        cursor: pointer;
    }

    .saved-header {
        margin-top: 14px;
        color: #244d3c;
        font-size: .92rem;
        font-weight: 800;
    }
    .saved-remove-btn {
        display: inline-block;
        width: 100%;
        border: 1px solid #e4c5c5;
        border-radius: 8px;
        background: #fff4f4;
        color: #983131;
        font-size: .74rem;
        font-weight: 800;
        padding: 5px 6px;
        cursor: pointer;
        text-align: center;
    }

    .photo-help {
        color: #5f7f71;
        font-size: .84rem;
        margin-top: 8px;
    }
    .project-guide {
        margin-top: 16px;
        border: 1px solid #c8ddd0;
        border-radius: 12px;
        background: #f4faf7;
        color: #2f5b49;
        padding: 12px 14px;
    }
    .project-guide h3 {
        margin: 0 0 6px;
        color: #18553b;
        font-size: 1rem;
    }
    .project-guide ul {
        margin: 0;
        padding-left: 18px;
        font-size: .88rem;
    }
    .error-text {
        color: #a72828;
        font-size: 12px;
        font-weight: 700;
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
    .btn-disabled {
        opacity: .5;
        pointer-events: none;
        filter: grayscale(.2);
    }

    @media (max-width: 1100px) {
        .steps {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .panel {
            padding: 16px;
        }
    }
    @media (max-width: 700px) {
        .steps {
            grid-template-columns: 1fr;
        }
        .actions .btn {
            width: 100%;
            text-align: center;
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
            <article class="step-card current">
                <div class="step-num">2.</div>
                <h3 class="step-title">Sube fotos</h3>
                <p class="step-text">Carga imagenes de la propiedad para atraer mas interesados.</p>
            </article>
            <article class="step-card">
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
            <a href="/" class="crumb">Volver al inicio</a>
            <h1>Paso 2: Sube fotos de tu propiedad</h1>
            <p class="subtitle">Guarda tus fotos primero y luego continua con el paso de datos.</p>
        </header>

        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert error">
                <strong>Hay errores en la carga de fotos. Revisa e intenta nuevamente.</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="panel">
            <h2 class="section-title">Carga de fotos</h2>
            <p class="section-help">Minimo 1 foto. Maximo 12 fotos. Hasta 5MB por imagen.</p>

            <form action="{{ route('propiedades.fotos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="uploader" id="uploader">
                    <input id="fotos" class="native-file-input" name="fotos[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*" multiple>

                    <div class="uploader-top">
                        <div>
                            <p class="uploader-title">Sube imagenes claras de tu propiedad</p>
                            <p class="uploader-sub">Arrastra y suelta aqui o usa el boton para seleccionar.</p>
                        </div>
                        <button class="uploader-btn" id="open-picker" type="button">Seleccionar fotos</button>
                    </div>

                    <div class="uploader-counter" id="file-counter">No hay fotos seleccionadas.</div>
                    <div class="preview-grid" id="preview-grid"></div>
                </div>

                <div class="photo-help">Fotos guardadas actualmente: <strong>{{ $fotosCount }}</strong></div>
                @error('fotos') <span class="error-text">{{ $message }}</span> @enderror
                @if ($errors->has('fotos.*'))
                    <span class="error-text">{{ $errors->first('fotos.*') }}</span>
                @endif

                <div style="margin-top: 14px;">
                    <button class="btn btn-primary" type="submit">Guardar fotos</button>
                </div>
            </form>

            @if ($fotosCount > 0)
                <div class="saved-header">Fotos guardadas ({{ $fotosCount }})</div>
                <div class="saved-grid">
                    @foreach ($fotosTemporales as $index => $path)
                        <article class="preview-item">
                            <img class="preview-image" src="{{ route('propiedades.fotos.preview', $index) }}" alt="Foto {{ $index + 1 }}">
                            <div class="preview-meta">
                                <div class="preview-name">Foto {{ $index + 1 }}</div>
                                <form method="POST" action="{{ route('propiedades.fotos.remove', $index) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="saved-remove-btn">Quitar</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="project-guide" aria-label="Guia para publicar proyecto inmobiliario">
            <h3>Si vas a publicar un proyecto inmobiliario</h3>
            <ul>
                <li>En el paso 3 elige un tipo que diga "(proyecto)".</li>
                <li>La operacion para proyecto es venta y se ajusta automaticamente.</li>
                <li>En titulo y descripcion indica nombre del proyecto, etapas, entrega y precios desde.</li>
            </ul>
        </section>

        <div class="actions">
            <a class="btn btn-link" href="/">Cancelar</a>
            <a class="btn btn-primary {{ $fotosCount === 0 ? 'btn-disabled' : '' }}" href="{{ route('propiedades.datos.create') }}">Continuar al paso 3</a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    const MAX_FILES = 12;
    const existingCount = {{ $fotosCount }};
    const uploader = document.getElementById('uploader');
    const fotosInput = document.getElementById('fotos');
    const openPickerBtn = document.getElementById('open-picker');
    const previewGrid = document.getElementById('preview-grid');
    const counter = document.getElementById('file-counter');

    let selectedFiles = [];

    const fileKey = (file) => `${file.name}-${file.size}-${file.lastModified}`;

    const syncInputFiles = () => {
        const transfer = new DataTransfer();
        selectedFiles.forEach((file) => transfer.items.add(file));
        fotosInput.files = transfer.files;
        renderPreviews();
    };

    const removeFile = (index) => {
        selectedFiles = selectedFiles.filter((_, current) => current !== index);
        syncInputFiles();
    };

    const renderPreviews = () => {
        previewGrid.innerHTML = '';

        if (selectedFiles.length === 0) {
            counter.textContent = `No hay fotos seleccionadas. Tienes ${existingCount} guardada(s).`;
            return;
        }

        counter.textContent = `${selectedFiles.length} foto(s) lista(s) para guardar`;

        selectedFiles.forEach((file, index) => {
            const item = document.createElement('article');
            item.className = 'preview-item';

            const img = document.createElement('img');
            img.className = 'preview-image';
            img.src = URL.createObjectURL(file);
            img.alt = file.name;

            const meta = document.createElement('div');
            meta.className = 'preview-meta';

            const name = document.createElement('div');
            name.className = 'preview-name';
            name.textContent = file.name;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'preview-remove';
            removeBtn.textContent = 'Quitar';
            removeBtn.addEventListener('click', () => removeFile(index));

            meta.appendChild(name);
            meta.appendChild(removeBtn);
            item.appendChild(img);
            item.appendChild(meta);
            previewGrid.appendChild(item);
        });
    };

    const addFiles = (incomingFiles) => {
        incomingFiles.forEach((file) => {
            if (!file.type.startsWith('image/')) {
                return;
            }

            if ((selectedFiles.length + existingCount) >= MAX_FILES) {
                return;
            }

            const alreadyAdded = selectedFiles.some((existing) => fileKey(existing) === fileKey(file));
            if (!alreadyAdded) {
                selectedFiles.push(file);
            }
        });

        syncInputFiles();
    };

    if (openPickerBtn && fotosInput) {
        openPickerBtn.addEventListener('click', () => fotosInput.click());

        fotosInput.addEventListener('change', (event) => {
            addFiles(Array.from(event.target.files || []));
        });
    }

    if (uploader) {
        ['dragenter', 'dragover'].forEach((eventName) => {
            uploader.addEventListener(eventName, (event) => {
                event.preventDefault();
                event.stopPropagation();
                uploader.classList.add('dragging');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            uploader.addEventListener(eventName, (event) => {
                event.preventDefault();
                event.stopPropagation();
                uploader.classList.remove('dragging');
            });
        });

        uploader.addEventListener('drop', (event) => {
            const dropped = Array.from(event.dataTransfer?.files || []);
            addFiles(dropped);
        });
    }

    renderPreviews();
</script>
@endsection
