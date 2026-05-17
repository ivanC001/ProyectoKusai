@extends('layouts.client')

@section('title', 'Como publicar en Kusay.pe')

@section('content')
    <!-- Estilos usados por esta vista: resources/css/pages/portal-index.css -->
    <section class="guia-sec sec" id="como-publicar">
        <div class="guia-head">
            <p class="eyebrow guia-eyebrow">
                <!-- ICONO ENCABEZADO: reemplaza solo este <i> -->
                <span class="guia-eyebrow-icon"><i class="bi bi-camera-fill" aria-hidden="true"></i></span>
                Fácil y rápido
            </p>
            <h1 class="stitle guia-title">¿Cómo publico mi propiedad?</h1>
            <p class="ssub guia-sub">Sigue estos 4 pasos. No necesitas saber mucho de internet. ¡En 10 minutos está listo!</p>
            <p class="guia-seo-support">Cómo publicar tu propiedad en Kusay.pe</p>
        </div>

        <div class="guia-steps">
            <article class="gs">
                <div class="gs-num">
                    <!-- ICONO PASO 1: reemplaza solo este <i> -->
                    <span class="gs-ico"><i class="bi bi-person-fill" aria-hidden="true"></i></span>
                    <div class="gs-num-badge">1</div>
                </div>
                <h3>Crea tu cuenta gratis</h3>
                <p class="gs-desc">Solo tu nombre, celular y correo. No cuesta nada.</p>
                <div class="gs-tip"><strong>Consejo:</strong>Puedes ingresar con Google. Si no tienes correo, pídele ayuda a un familiar.</div>
            </article>

            <article class="gs">
                <div class="gs-num">
                    <!-- ICONO PASO 2: reemplaza solo este <i> -->
                    <span class="gs-ico"><i class="bi bi-camera-fill" aria-hidden="true"></i></span>
                    <div class="gs-num-badge">2</div>
                </div>
                <h3>Saca fotos a tu propiedad</h3>
                <p class="gs-desc">Con tu celular toma fotos claras. Puedes subir hasta 5 fotos gratis.</p>
                <div class="gs-tip"><strong>Consejo:</strong>Toma las fotos de día con buena luz. Muestra la entrada, interior y alrededores.</div>
            </article>

            <article class="gs">
                <div class="gs-num">
                    <!-- ICONO PASO 3: reemplaza solo este <i> -->
                    <span class="gs-ico"><i class="bi bi-pencil-fill" aria-hidden="true"></i></span>
                    <div class="gs-num-badge">3</div>
                </div>
                <h3>Llena el formulario</h3>
                <p class="gs-desc">Escribe el precio, el tamaño y la ubicación de tu propiedad.</p>
                <div class="gs-tip"><strong>Consejo:</strong>Escribe referencias: "a 2 cuadras del mercado central". Ayuda al comprador a ubicarse.</div>
            </article>

            <article class="gs">
                <div class="gs-num">
                    <!-- ICONO PASO 4: reemplaza solo este <i> -->
                    <span class="gs-ico"><i class="bi bi-phone-fill" aria-hidden="true"></i></span>
                    <div class="gs-num-badge">4</div>
                </div>
                <h3>¡Publica y espera contactos!</h3>
                <p class="gs-desc">Los compradores te escribirán por WhatsApp o correo directamente.</p>
                <div class="gs-tip"><strong>Consejo:</strong>Responde rápido. El que responde primero tiene más chances de vender.</div>
            </article>
        </div>

        <div class="guia-alerta">
            <span class="guia-alerta-icon"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i></span>
            <p><strong>Importante:</strong> Kusay.pe conecta compradores y vendedores. <strong>Los pagos y tratos los acuerdan directamente entre ellos.</strong> Siempre verifica documentos y formaliza con notaría.</p>
        </div>

        <div class="guia-cta-wrap">
            @auth
                <a href="{{ route('propiedades.create') }}" class="btn btn-main guia-cta-btn">Publicar mi propiedad - Es gratis</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-main guia-cta-btn">Crear cuenta y publicar gratis</a>
            @endauth
        </div>
    </section>

    <section class="sec verify-seal" id="sello-verificado">
        <div class="shead">
            <div>
                <p class="eyebrow">Confianza</p>
                <h2 class="stitle">Más confianza con usuarios verificados</h2>
                <p class="ssub">Los perfiles validados por Kusay generan mayor seguridad y mejor tasa de respuesta.</p>
            </div>
        </div>
        <div class="seal-grid">
            <article class="seal-card">
                <div class="seal-icon"><i class="bi bi-file-earmark-check-fill" aria-hidden="true"></i></div>
                <h3>DNI validado</h3>
                <p>El usuario envía DNI frontal y reverso para validar su identidad en el portal.</p>
                <span class="seal-chip"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Documento verificado</span>
            </article>
            <article class="seal-card">
                <div class="seal-icon"><i class="bi bi-person-vcard-fill" aria-hidden="true"></i></div>
                <h3>Datos consistentes</h3>
                <p>El equipo revisa que nombre, DNI y cuenta correspondan a la misma persona.</p>
                <span class="seal-chip"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Identidad confirmada</span>
            </article>
            <article class="seal-card">
                <div class="seal-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></div>
                <h3>Contacto confiable</h3>
                <p>Los datos de contacto son revisados para mejorar la calidad de los leads y conversaciones.</p>
                <span class="seal-chip"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Usuario confiable</span>
            </article>
        </div>
    </section>

    <section class="sec portal-feedback" id="mejoras-como-publicar">
        <div class="shead">
            <div>
                <p class="eyebrow">Tu opinión</p>
                <h2 class="stitle">Comentarios, reseñas y sugerencias</h2>
                <p class="ssub">Ayúdanos a mejorar el proceso de publicación para todos.</p>
            </div>
            <div class="portal-feedback-stats">
                <span><i class="bi bi-star-fill" aria-hidden="true"></i> {{ $promedioPuntajePortal ?? '0.0' }}/5</span>
                <span><i class="bi bi-chat-left-text-fill" aria-hidden="true"></i> {{ $totalComentariosPortal }} reseña(s)</span>
            </div>
        </div>

        @if (session('portal_feedback_success'))
            <p class="portal-feedback-alert success">{{ session('portal_feedback_success') }}</p>
        @endif

        @if ($errors->portalFeedback->any())
            <p class="portal-feedback-alert error">Revisa los datos del formulario antes de enviar.</p>
        @endif

        @auth
            <form class="portal-feedback-form" method="POST" action="{{ route('portal.como-publicar.comentarios.store') }}">
                @csrf
                <div class="portal-feedback-grid">
                    <div class="portal-feedback-field">
                        <label>Puntuación general</label>
                        <div class="portal-feedback-rating" role="radiogroup" aria-label="Puntuación de la experiencia">
                            @for ($i = 5; $i >= 1; $i--)
                                <input
                                    type="radio"
                                    id="portal_puntaje_{{ $i }}"
                                    name="puntaje"
                                    value="{{ $i }}"
                                    @checked((string) old('puntaje', '5') === (string) $i)
                                    required
                                >
                                <label for="portal_puntaje_{{ $i }}" title="{{ $i }} estrella(s)">
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                </label>
                            @endfor
                        </div>
                        <small class="portal-feedback-hint">Marca de 1 a 5 estrellas según tu experiencia.</small>
                        @error('puntaje', 'portalFeedback') <span class="portal-feedback-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="portal-feedback-field full">
                        <label for="comentario">Comentario</label>
                        <textarea id="comentario" name="comentario" placeholder="Cuéntanos cómo te fue publicando tu propiedad..." required>{{ old('comentario') }}</textarea>
                        @error('comentario', 'portalFeedback') <span class="portal-feedback-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="portal-feedback-field full">
                        <label for="sugerencia">Sugerencia (opcional)</label>
                        <textarea id="sugerencia" name="sugerencia" placeholder="¿Qué mejorarías para que publicar sea más fácil?">{{ old('sugerencia') }}</textarea>
                        <small class="portal-feedback-hint">Tu sugerencia solo la verá el equipo administrador de Kusay.</small>
                        @error('sugerencia', 'portalFeedback') <span class="portal-feedback-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-main">Enviar reseña</button>
            </form>
        @else
            <p class="portal-feedback-login">
                <a href="{{ route('login') }}">Inicia sesión</a> para dejar tu comentario y sugerencias.
            </p>
        @endauth

        <div class="portal-feedback-list">
            @forelse ($comentariosPortal as $comentario)
                <article class="portal-feedback-card">
                    <div class="portal-feedback-card-head">
                        <strong>{{ trim(($comentario->usuario?->name ?? 'Usuario').' '.($comentario->usuario?->apellidos ?? '')) }}</strong>
                        <span class="portal-feedback-date">{{ optional($comentario->created_at)->format('Y-m-d H:i') }}</span>
                    </div>
                    <p class="portal-feedback-stars" aria-label="Puntaje {{ $comentario->puntaje }} de 5">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $comentario->puntaje ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                        @endfor
                    </p>
                    <p class="portal-feedback-text">{{ $comentario->comentario }}</p>
                </article>
            @empty
                <div class="empty-list">Aún no hay reseñas de esta sección. Sé el primero en comentar.</div>
            @endforelse
        </div>
    </section>
@endsection
