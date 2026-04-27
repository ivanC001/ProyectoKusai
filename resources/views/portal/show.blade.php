<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $propiedad->titulo }} | Kusay.pe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f3f7f4;
            --paper: #fff;
            --line: #d0ddd6;
            --ink: #1b4434;
            --soft: #607f71;
            --brand: #1a5d41;
            --accent: #b24f28;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--ink);
            background: var(--bg);
        }
        body.no-scroll {
            overflow: hidden;
        }
        a { color: inherit; text-decoration: none; }
        .wrap {
            width: min(1160px, 94vw);
            margin: 0 auto;
            padding: 24px 0 42px;
        }
        .brand-banner {
            border: 1px solid #bed2c7;
            border-radius: 18px;
            background:
                radial-gradient(circle at 8% 15%, rgba(96, 195, 137, .2) 0%, transparent 30%),
                linear-gradient(130deg, #113d2b, #1b6949);
            color: #f3fbf6;
            padding: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 14px;
        }
        .brand-main {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: "Fraunces", serif;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -.4px;
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
        }
        .brand-logo .leaf {
            font-size: 1.02em;
            line-height: 1;
            filter: drop-shadow(0 2px 1px rgba(0, 0, 0, .2));
        }
        .brand-logo .dot {
            color: #df8347;
        }
        .brand-logo .pe {
            color: #6fd6a2;
        }
        .brand-copy h2 {
            margin: 0 0 4px;
            font-family: "Fraunces", serif;
            font-size: clamp(1.15rem, 2vw, 1.45rem);
            line-height: 1.1;
        }
        .brand-copy p {
            margin: 0;
            color: rgba(232, 248, 239, .9);
            font-size: .93rem;
        }
        .brand-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .crumb {
            font-size: .92rem;
            font-weight: 700;
            color: #567768;
        }
        .btn {
            border-radius: 10px;
            border: 1px solid transparent;
            font-size: .9rem;
            font-weight: 800;
            padding: 10px 14px;
        }
        .btn-outline {
            border-color: #c2d4ca;
            background: #fff;
            color: #2d5a48;
        }
        .btn-main {
            background: linear-gradient(130deg, #2d8f5f, #17573c);
            color: #fff;
        }
        .hero {
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            background: var(--paper);
            margin-bottom: 14px;
        }
        .hero-cover {
            width: 100%;
            height: clamp(240px, 42vw, 480px);
            object-fit: cover;
            background: #dce9e2;
        }
        .hero-empty {
            width: 100%;
            height: clamp(240px, 42vw, 480px);
            display: grid;
            place-items: center;
            color: #587969;
            background: linear-gradient(145deg, #dbe7e1, #ecf3ef);
            font-weight: 700;
        }
        .gallery {
            padding: 12px;
            border-top: 1px solid #e2ece6;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 8px;
        }
        .gallery img {
            width: 100%;
            height: 84px;
            object-fit: cover;
            border-radius: 9px;
            border: 1px solid #d2ddd7;
            background: #dce9e2;
        }
        .layout {
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 14px;
        }
        .panel {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--paper);
            padding: 18px;
        }
        .title {
            margin: 0 0 8px;
            font-family: "Fraunces", serif;
            font-size: clamp(1.8rem, 3.2vw, 2.5rem);
            line-height: 1.1;
        }
        .meta {
            margin: 0 0 10px;
            color: var(--soft);
            font-weight: 600;
        }
        .price {
            margin: 0 0 12px;
            color: var(--accent);
            font-family: "Fraunces", serif;
            font-size: clamp(1.7rem, 3.3vw, 2.3rem);
            line-height: 1;
        }
        .chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .chip {
            border: 1px solid #cadad1;
            border-radius: 999px;
            background: #f5faf7;
            color: #3f6756;
            font-size: .78rem;
            font-weight: 800;
            padding: 5px 10px;
        }
        .desc {
            color: #355b4b;
            white-space: pre-line;
            line-height: 1.65;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .item {
            border: 1px solid #d7e4dd;
            border-radius: 11px;
            background: #f6faf8;
            padding: 10px;
        }
        .item p {
            margin: 0;
        }
        .item .label {
            color: #628173;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 800;
        }
        .item .value {
            margin-top: 5px;
            font-size: 1rem;
            font-weight: 700;
        }
        .side-title {
            margin: 0 0 8px;
            font-family: "Fraunces", serif;
            font-size: 1.2rem;
        }
        .contact {
            display: grid;
            gap: 10px;
        }
        .contact .line {
            border: 1px solid #d5e3db;
            border-radius: 10px;
            background: #f6faf8;
            padding: 10px;
        }
        .contact .line b {
            display: block;
            margin-bottom: 2px;
            font-size: .8rem;
            color: #5f7f71;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .contact-open {
            margin-top: 10px;
            width: 100%;
            text-align: center;
            cursor: pointer;
        }
        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 120;
            background: rgba(13, 34, 25, .62);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 14px;
        }
        .modal-backdrop.is-open {
            display: flex;
        }
        .contact-modal {
            width: min(520px, 95vw);
            border: 1px solid #cad8d1;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 24px 56px rgba(8, 24, 17, .35);
            padding: 16px;
        }
        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .modal-title {
            margin: 0;
            font-family: "Fraunces", serif;
            font-size: 1.25rem;
            line-height: 1.1;
        }
        .modal-close {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid #cfdbd4;
            background: #f6faf8;
            color: #2d5a48;
            font-size: 1.15rem;
            cursor: pointer;
        }
        .modal-sub {
            margin: 8px 0 12px;
            color: #638072;
            font-size: .9rem;
        }
        .contact-actions {
            margin-top: 8px;
            display: grid;
            gap: 8px;
        }
        .contact-btn {
            border-radius: 11px;
            border: 1px solid #c8d8cf;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-weight: 800;
            transition: transform .16s ease, box-shadow .16s ease;
        }
        .contact-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(17, 57, 40, .12);
        }
        .contact-btn .left {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .contact-btn .icon {
            width: 24px;
            height: 24px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            font-size: .82rem;
            background: rgba(255, 255, 255, .24);
            border: 1px solid rgba(255, 255, 255, .4);
        }
        .contact-btn .meta {
            margin: 0;
            font-weight: 600;
            font-size: .82rem;
            color: inherit;
            opacity: .9;
        }
        .contact-btn.whatsapp {
            background: linear-gradient(130deg, #1ea85a, #167744);
            color: #fff;
            border-color: #1a884f;
        }
        .contact-btn.call {
            background: #f0f7f3;
            color: #1a5d41;
            border-color: #bdd4c7;
        }
        .contact-btn.mail {
            background: #eef5ff;
            color: #204d7e;
            border-color: #c4d6eb;
        }
        .contact-btn.disabled {
            opacity: .6;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
            transform: none;
        }
        .mini-grid {
            margin-top: 10px;
            display: grid;
            gap: 10px;
        }
        .mini-card {
            border: 1px solid #d2ddd7;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }
        .mini-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            background: #dce9e2;
        }
        .mini-card .txt {
            padding: 10px;
        }
        .mini-card h4 {
            margin: 0 0 4px;
            font-size: .95rem;
            color: #1f4b3a;
        }
        .mini-card p {
            margin: 0;
            font-size: .85rem;
            color: #688678;
        }
        @media (max-width: 960px) {
            .brand-banner {
                align-items: flex-start;
                flex-direction: column;
            }
            .brand-main {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .brand-logo {
                font-size: 1.6rem;
            }
            .brand-actions {
                width: 100%;
                justify-content: flex-start;
            }
            .layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        @php
            $nombrePublicador = trim(($propiedad->usuario?->name ?? '').' '.($propiedad->usuario?->apellidos ?? '')) ?: 'Anunciante';
            $correoContacto = $propiedad->usuario?->email;
            $telefonoContacto = $propiedad->usuario?->telefono;
            $whatsappContacto = $propiedad->usuario?->whatsapp ?: $telefonoContacto;
            $publicarHref = auth()->check() ? route('propiedades.create') : route('register');

            $normalizarNumero = static function (?string $value): ?string {
                if (! $value) {
                    return null;
                }

                $soloDigitos = preg_replace('/\D+/', '', $value);
                if (! $soloDigitos) {
                    return null;
                }

                if (strlen($soloDigitos) === 9) {
                    return '51'.$soloDigitos;
                }

                return $soloDigitos;
            };

            $telefonoNormalizado = $normalizarNumero($telefonoContacto);
            $whatsappNormalizado = $normalizarNumero($whatsappContacto);
            $telefonoHref = $telefonoNormalizado ? 'tel:+'.$telefonoNormalizado : null;
            $whatsappHref = $whatsappNormalizado ? 'https://wa.me/'.$whatsappNormalizado : null;
            $correoHref = $correoContacto ? 'mailto:'.$correoContacto : null;
        @endphp

        <section class="brand-banner">
            <div class="brand-main">
                <a href="{{ route('home') }}" class="brand-logo">
                    <span class="leaf">&#127807;</span>
                    Kusay<span class="dot">.</span><span class="pe">pe</span>
                </a>
                <div class="brand-copy">
                    <h2>Tu propiedad se vende mejor en Kusay.pe</h2>
                    <p>Conecta rapido con compradores y arrendatarios reales en la selva y sierra del Peru.</p>
                </div>
            </div>
            <div class="brand-actions">
                <a class="btn btn-main" href="{{ $publicarHref }}">Publica tu propiedad</a>
                <a class="btn btn-outline" href="{{ route('home') }}#props">Explorar anuncios</a>
            </div>
        </section>

        <div class="top">
            <a class="crumb" href="{{ route('home') }}">Volver al portal</a>
            <div>
                <a class="btn btn-outline" href="{{ route('home') }}">Seguir buscando</a>
                @auth
                    <a class="btn btn-main" href="{{ route('propiedades.create') }}">Publica gratis</a>
                @endauth
            </div>
        </div>

        <section class="hero">
            @if ($propiedad->portadaImagen)
                <img class="hero-cover" src="{{ route('portal.propiedades.imagen', [$propiedad, $propiedad->portadaImagen]) }}" alt="Portada de {{ $propiedad->titulo }}">
            @else
                <div class="hero-empty">Sin foto principal</div>
            @endif

            @if ($propiedad->imagenes->isNotEmpty())
                <div class="gallery">
                    @foreach ($propiedad->imagenes as $imagen)
                        <img src="{{ route('portal.propiedades.imagen', [$propiedad, $imagen]) }}" alt="Foto {{ $loop->iteration }} de {{ $propiedad->titulo }}">
                    @endforeach
                </div>
            @endif
        </section>

        <section class="layout">
            <article class="panel">
                <h1 class="title">{{ $propiedad->titulo }}</h1>
                <p class="meta">
                    {{ $propiedad->ubicacion?->distrito ?? 'Sin distrito' }},
                    {{ $propiedad->ubicacion?->provincia ?? 'Sin provincia' }},
                    {{ $propiedad->ubicacion?->departamento ?? 'Sin departamento' }}
                </p>
                <p class="price">S/ {{ number_format((float) $propiedad->precio, 2, '.', ',') }}</p>
                <div class="chips">
                    <span class="chip">{{ ucfirst($propiedad->tipo) }}</span>
                    <span class="chip">{{ $propiedad->tipoPropiedad?->nombre ?? 'Sin tipo' }}</span>
                    <span class="chip">{{ ucfirst($propiedad->estado) }}</span>
                </div>
                <p class="desc">{{ $propiedad->descripcion }}</p>

                <div class="grid">
                    <div class="item"><p class="label">Area</p><p class="value">{{ $propiedad->area ? number_format((float) $propiedad->area, 2, '.', ',').' m2' : 'No especificada' }}</p></div>
                    <div class="item"><p class="label">Dormitorios</p><p class="value">{{ $propiedad->habitaciones ?? 'No especificado' }}</p></div>
                    <div class="item"><p class="label">Banos</p><p class="value">{{ $propiedad->banos ?? 'No especificado' }}</p></div>
                    <div class="item"><p class="label">Direccion</p><p class="value">{{ $propiedad->direccion }}</p></div>
                </div>
            </article>

            <aside class="panel">
                <h2 class="side-title">Contacto</h2>
                <div class="contact">
                    <div class="line">
                        <b>Publicado por</b>
                        <span>{{ $nombrePublicador }}</span>
                    </div>
                    <div class="line">
                        <b>Correo</b>
                        <span>{{ $correoContacto ?? 'No disponible' }}</span>
                    </div>
                    <div class="line">
                        <b>Telefono</b>
                        <span>{{ $telefonoContacto ?? 'No disponible' }}</span>
                    </div>
                    <div class="line">
                        <b>WhatsApp</b>
                        <span>{{ $whatsappContacto ?? 'No disponible' }}</span>
                    </div>
                </div>
                <button type="button" class="btn btn-main contact-open" data-contact-open>Contactar anunciante</button>

                @if ($relacionadas->isNotEmpty())
                    <h2 class="side-title" style="margin-top:14px;">Relacionadas</h2>
                    <div class="mini-grid">
                        @foreach ($relacionadas as $item)
                            <a class="mini-card" href="{{ route('portal.propiedades.show', $item) }}">
                                @if ($item->portadaImagen)
                                    <img src="{{ route('portal.propiedades.imagen', [$item, $item->portadaImagen]) }}" alt="Portada de {{ $item->titulo }}">
                                @endif
                                <div class="txt">
                                    <h4>{{ $item->titulo }}</h4>
                                    <p>{{ $item->ubicacion?->distrito ?? 'Sin distrito' }} - S/ {{ number_format((float) $item->precio, 2, '.', ',') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </aside>
        </section>

        <div class="modal-backdrop" data-contact-modal aria-hidden="true">
            <div class="contact-modal" role="dialog" aria-modal="true" aria-labelledby="contact-modal-title">
                <div class="modal-head">
                    <h3 class="modal-title" id="contact-modal-title">Contactar a {{ $nombrePublicador }}</h3>
                    <button type="button" class="modal-close" data-contact-close aria-label="Cerrar modal">&times;</button>
                </div>
                <p class="modal-sub">Elige el canal para hablar con el anunciante ahora.</p>

                <div class="contact-actions">
                    @if ($whatsappHref)
                        <a class="contact-btn whatsapp" href="{{ $whatsappHref }}" target="_blank" rel="noopener">
                            <span class="left"><span class="icon">&#128241;</span>WhatsApp</span>
                            <span class="meta">{{ $whatsappContacto }}</span>
                        </a>
                    @else
                        <span class="contact-btn whatsapp disabled">
                            <span class="left"><span class="icon">&#128241;</span>WhatsApp</span>
                            <span class="meta">No disponible</span>
                        </span>
                    @endif

                    @if ($telefonoHref)
                        <a class="contact-btn call" href="{{ $telefonoHref }}">
                            <span class="left"><span class="icon">&#9742;</span>Llamar</span>
                            <span class="meta">{{ $telefonoContacto }}</span>
                        </a>
                    @else
                        <span class="contact-btn call disabled">
                            <span class="left"><span class="icon">&#9742;</span>Llamar</span>
                            <span class="meta">No disponible</span>
                        </span>
                    @endif

                    @if ($correoHref)
                        <a class="contact-btn mail" href="{{ $correoHref }}">
                            <span class="left"><span class="icon">&#9993;</span>Correo</span>
                            <span class="meta">{{ $correoContacto }}</span>
                        </a>
                    @else
                        <span class="contact-btn mail disabled">
                            <span class="left"><span class="icon">&#9993;</span>Correo</span>
                            <span class="meta">No disponible</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.querySelector('[data-contact-modal]');
            const openButton = document.querySelector('[data-contact-open]');
            if (!modal || !openButton) {
                return;
            }

            const closeButtons = modal.querySelectorAll('[data-contact-close]');

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('no-scroll');
            };

            const openModal = () => {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('no-scroll');
            };

            openButton.addEventListener('click', openModal);
            closeButtons.forEach((button) => button.addEventListener('click', closeModal));

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        })();
    </script>
</body>
</html>
