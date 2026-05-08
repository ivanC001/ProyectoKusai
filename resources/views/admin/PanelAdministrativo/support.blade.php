@extends('layouts.admin')

@section('title', 'Admin | Administrar terminos y soporte')
@section('page_title', 'Administrar terminos y soporte')
@section('page_subtitle', 'Edita terminos, privacidad y centro de ayuda del portal')

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
    .support-grid {
        margin-top: 12px;
        display: grid;
        grid-template-columns: minmax(340px, 420px) 1fr;
        gap: 12px;
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
    .field input,
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
        min-height: 280px;
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
        font-size: .82rem;
        font-weight: 800;
        padding: 8px 12px;
        cursor: pointer;
    }
    .btn-main {
        color: #fff;
        background: linear-gradient(130deg, #1f8b58, #124c35);
    }
    .support-preview {
        border: 1px solid #d3dfd8;
        border-radius: 10px;
        background: #f8fcfa;
        padding: 10px;
        max-height: 640px;
        overflow: auto;
    }
    .support-preview h4 {
        margin: 0 0 8px;
        font-size: .9rem;
        color: #194934;
    }
    .support-preview p {
        margin: 0 0 8px;
        font-size: .8rem;
        color: #4f7062;
        line-height: 1.4;
    }
    .support-preview ul {
        margin: 0;
        padding-left: 18px;
    }
    .support-preview li {
        margin-bottom: 5px;
        color: #2f5f49;
        font-size: .8rem;
    }
    @media (max-width: 1120px) {
        .support-grid {
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

    <section class="panel">
        <div class="panel-head">
            <h2>Terminos y soporte del portal</h2>
            <span>Editable desde admin</span>
        </div>
        <p>Selecciona una pagina y actualiza su contenido. Este texto se muestra en el footer y vistas de soporte.</p>

        <div class="support-grid">
            <form method="POST" action="{{ route('admin.PanelAdministrativo.soporte.update') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="return_to" value="support">

                <div class="field">
                    <label for="support_slug">Pagina de soporte</label>
                    <select id="support_slug" name="support_slug" onchange="if (this.value) { window.location='{{ route('admin.PanelAdministrativo.soporte') }}?support_page=' + this.value; }">
                        @foreach ($supportPages as $slug => $label)
                            <option value="{{ $slug }}" {{ $selectedSupportPage === $slug ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('support_slug') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="title">Titulo</label>
                    <input id="title" name="title" type="text" value="{{ old('title', (string) ($supportPayload['title'] ?? '')) }}">
                    @error('title') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="summary">Resumen</label>
                    <textarea id="summary" name="summary" rows="4">{{ old('summary', (string) ($supportPayload['summary'] ?? '')) }}</textarea>
                    @error('summary') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="updated_at">Fecha de actualizacion</label>
                    <input id="updated_at" name="updated_at" type="date" value="{{ old('updated_at', (string) ($supportPayload['updated_at'] ?? now()->toDateString())) }}">
                    @error('updated_at') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="sections_text">Contenido</label>
                    <textarea id="sections_text" name="sections_text">{{ old('sections_text', $supportSectionsText) }}</textarea>
                    <div class="field-help">Formato: usa <strong>## Titulo</strong> para secciones, texto normal para parrafos y <strong>- item</strong> para bullets.</div>
                    @error('sections_text') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-main">Guardar contenido</button>
            </form>

            <aside class="support-preview">
                <h4>Vista previa actual</h4>
                <p><strong>{{ $supportPayload['title'] ?? 'Sin titulo' }}</strong></p>
                <p>{{ $supportPayload['summary'] ?? '' }}</p>
                @foreach (($supportPayload['sections'] ?? []) as $section)
                    <h4>{{ $section['title'] ?? '' }}</h4>
                    @foreach (($section['paragraphs'] ?? []) as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    @if (! empty($section['bullets']))
                        <ul>
                            @foreach ($section['bullets'] as $bullet)
                                <li>{{ $bullet }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </aside>
        </div>
    </section>
@endsection
