@extends('layouts.client')

@section('title', 'Propiedad Publicada | Kusay.pe')

@section('styles')
<style>
    .done-wrap {
        width: min(980px, 94vw);
        margin: 0 auto;
    }
    .steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }
    .step-card {
        background: #eef7f2;
        border: 1px solid #b8d6c5;
        border-radius: 14px;
        padding: 14px;
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
        font-weight: 800;
        font-size: 1.2rem;
        color: #0f5a3b;
        margin-bottom: 6px;
    }
    .step-title {
        margin: 0 0 4px;
        font-size: 1rem;
        font-weight: 800;
        color: #194333;
    }
    .step-text {
        margin: 0;
        font-size: .85rem;
        color: #5b7d6e;
    }
    .result-card {
        background: #fff;
        border: 1px solid #d4e1d9;
        border-radius: 16px;
        box-shadow: 0 16px 30px rgba(11, 49, 33, .1);
        padding: 20px;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: #d6f4e3;
        color: #0c5a3a;
        border: 1px solid #a7d8bf;
        font-weight: 800;
        font-size: .82rem;
        padding: 6px 10px;
        margin-bottom: 8px;
    }
    h1 {
        margin: 0 0 6px;
        font-family: "Fraunces", serif;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        color: #163f30;
    }
    .lead {
        margin: 0 0 14px;
        color: #5f7f71;
        font-size: 1rem;
    }
    .meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 16px;
    }
    .meta-box {
        background: #f3f8f5;
        border: 1px solid #d6e3db;
        border-radius: 12px;
        padding: 10px;
    }
    .meta-label {
        display: block;
        color: #6b8a7d;
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: 2px;
        text-transform: uppercase;
    }
    .meta-value {
        color: #1f4738;
        font-weight: 800;
        font-size: .94rem;
    }
    .actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid transparent;
        font-weight: 800;
        text-decoration: none;
        font-size: .93rem;
    }
    .btn-main {
        background: linear-gradient(150deg, #186245, #124f37);
        color: #fff;
    }
    .btn-outline {
        border-color: #c6d5cc;
        color: #2f5d4b;
        background: #f8fbf9;
    }
    .countdown {
        margin-left: auto;
        color: #6d8a7e;
        font-size: .88rem;
        font-weight: 700;
    }
    @media (max-width: 860px) {
        .steps,
        .meta {
            grid-template-columns: 1fr;
        }
        .countdown {
            width: 100%;
            margin-left: 0;
        }
    }
</style>
@endsection

@section('content')
    <div class="done-wrap">
        <section class="steps">
            <article class="step-card done">
                <div class="step-num">1.</div>
                <h3 class="step-title">Crea tu cuenta</h3>
                <p class="step-text">Registro rapido con correo y telefono.</p>
            </article>
            <article class="step-card done">
                <div class="step-num">2.</div>
                <h3 class="step-title">Sube fotos</h3>
                <p class="step-text">Imagenes claras para atraer mas interesados.</p>
            </article>
            <article class="step-card done">
                <div class="step-num">3.</div>
                <h3 class="step-title">Completa datos</h3>
                <p class="step-text">Precio, metraje, ubicacion y caracteristicas.</p>
            </article>
            <article class="step-card current">
                <div class="step-num">4.</div>
                <h3 class="step-title">Publica y recibe contactos</h3>
                <p class="step-text">Tu aviso quedo publicado y ya puede recibir mensajes.</p>
            </article>
        </section>

        <article class="result-card">
            <div class="badge">Publicacion completada</div>
            <h1>Propiedad registrada y publicada</h1>
            <p class="lead">Tu anuncio se guardo correctamente y ya esta visible para potenciales compradores.</p>

            <div class="meta">
                <div class="meta-box">
                    <span class="meta-label">Codigo</span>
                    <span class="meta-value">#{{ $propiedad->id }}</span>
                </div>
                <div class="meta-box">
                    <span class="meta-label">Titulo</span>
                    <span class="meta-value">{{ $propiedad->titulo }}</span>
                </div>
                <div class="meta-box">
                    <span class="meta-label">Fotos subidas</span>
                    <span class="meta-value">{{ $fotosCount }}</span>
                </div>
            </div>

            <div class="actions">
                <a href="{{ url('/') }}" class="btn btn-main">Terminar e ir al inicio</a>
                <a href="{{ route('propiedades.mine') }}" class="btn btn-outline">Ir a mis publicaciones</a>
                <a href="{{ route('propiedades.create') }}" class="btn btn-outline">Publicar otra propiedad</a>
                <span class="countdown">Redirigiendo al inicio en <strong id="seconds">8</strong>s...</span>
            </div>
        </article>
    </div>
@endsection

@section('scripts')
<script>
    const secondsEl = document.getElementById('seconds');
    let seconds = 8;

    const timer = setInterval(() => {
        seconds -= 1;
        if (secondsEl) {
            secondsEl.textContent = String(seconds);
        }

        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = '{{ url('/') }}';
        }
    }, 1000);
</script>
@endsection
