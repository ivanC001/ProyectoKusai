<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kusay.pe')</title>
    <!-- Icono que se muestra en la pestaña del navegador -->
    <link rel="icon" type="image/png" href="{{ asset('assets/image/kusay-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/image/kusay-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/client-app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    @php
        $homeUrl = route('home');
        $isHomeRoute = request()->routeIs('home');
        $isCreateRoute = request()->routeIs('propiedades.create');
        $isHowToPublishRoute = request()->routeIs('portal.como-publicar');
        $isMineRoute = request()->routeIs('propiedades.mine');
        $isSolicitudesRoute = request()->routeIs('propiedades.solicitudes');
        $authUser = auth()->user();
        $userFullName = $authUser ? trim(($authUser->name ?? '').' '.($authUser->apellidos ?? '')) : '';
        $userInitial = $authUser ? strtoupper(substr(trim($authUser->name ?? 'U'), 0, 1)) : 'U';
        $solicitudesRecibidasCount = $authUser ? $authUser->unreadSolicitudesCount() : 0;
    @endphp
    <!-- MENU: estilos en resources/css/app.css y resources/css/layouts/client.css -->
    <header class="client-nav">
        <div class="client-nav-wrap">
            <!-- Logo de marca -->
            <a href="{{ route('home') }}" class="brand-logo">
                <img src="{{ asset('assets/image/kusay-icon.png') }}" alt="Kusay" class="logo-icon">
                <img src="{{ asset('assets/image/kusay-wordmark.png') }}" alt="Kusay.pe" class="logo-text">
            </a>

            <ul class="client-links">
                <li><a href="{{ $homeUrl }}#props" class="nav-hash-link {{ $isHomeRoute ? 'is-active home-default' : '' }}" data-nav-target="props">Propiedades</a></li>
                <li><a href="{{ route('portal.como-publicar') }}" class="{{ $isHowToPublishRoute || $isCreateRoute ? 'is-active' : '' }}">Como publicar</a></li>
                <li><a href="{{ $homeUrl }}#destacadas" class="nav-hash-link" data-nav-target="destacadas">Destacadas</a></li>
            </ul>
            <div class="client-actions">
                @auth
                    <a href="{{ route('propiedades.mine') }}" class="btn btn-outline btn-mine-nav {{ ($isMineRoute || $isSolicitudesRoute) ? 'is-active' : '' }}">
                        <span>Mis publicaciones</span>
                        @if ($solicitudesRecibidasCount > 0)
                            <span class="nav-alert-badge" aria-label="{{ $solicitudesRecibidasCount }} solicitudes recibidas">
                                {{ $solicitudesRecibidasCount > 99 ? '99+' : $solicitudesRecibidasCount }}
                            </span>
                        @endif
                    </a>
                    <details class="user-menu">
                        <summary class="btn btn-outline user-summary" aria-label="Abrir menu de usuario">
                            <span class="user-avatar">
                                @if ($authUser->tieneFotoPerfil())
                                    <img src="{{ route('profile.photo', ['v' => optional($authUser->updated_at)->timestamp]) }}" alt="Foto">
                                @else
                                    {{ $userInitial }}
                                @endif
                            </span>
                            <span class="user-fullname">{{ $userFullName }}</span>
                            <span class="user-caret"><i class="bi bi-chevron-down" aria-hidden="true"></i></span>
                        </summary>
                        <div class="user-dropdown">
                            <div class="user-dropdown-head">
                                <span class="user-dropdown-avatar">
                                    @if ($authUser->tieneFotoPerfil())
                                        <img src="{{ route('profile.photo', ['v' => optional($authUser->updated_at)->timestamp]) }}" alt="Foto de perfil">
                                    @else
                                        {{ $userInitial }}
                                    @endif
                                </span>
                                <div class="user-dropdown-copy">
                                    <p class="user-dropdown-label">Cuenta activa</p>
                                    <p class="user-dropdown-name">{{ $userFullName }}</p>
                                    <p class="user-dropdown-email">{{ $authUser->email }}</p>
                                </div>
                            </div>
                            <div class="user-dropdown-links">
                                <a href="{{ route('home', ['favoritos' => 1]) }}#props" class="user-item">
                                    <span class="user-item-icon"><i class="bi bi-heart" aria-hidden="true"></i></span>
                                    <span class="user-item-copy">
                                        <strong>Mis favoritos</strong>
                                        <small>Propiedades guardadas</small>
                                    </span>
                                    <i class="bi bi-chevron-right user-item-arrow" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('propiedades.mine') }}" class="user-item {{ $isMineRoute ? 'is-active' : '' }}">
                                    <span class="user-item-icon"><i class="bi bi-house-door" aria-hidden="true"></i></span>
                                    <span class="user-item-copy">
                                        <strong>Mis publicaciones</strong>
                                        <small>Gestiona tus avisos</small>
                                    </span>
                                    <i class="bi bi-chevron-right user-item-arrow" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('propiedades.solicitudes') }}" class="user-item {{ $isSolicitudesRoute ? 'is-active' : '' }}">
                                    <span class="user-item-icon"><i class="bi bi-chat-left-text" aria-hidden="true"></i></span>
                                    <span class="user-item-copy">
                                        <strong>Solicitudes recibidas</strong>
                                        <small>Contactos y mensajes</small>
                                    </span>
                                    @if ($solicitudesRecibidasCount > 0)
                                        <span class="menu-alert-badge">
                                            {{ $solicitudesRecibidasCount > 99 ? '99+' : $solicitudesRecibidasCount }}
                                        </span>
                                    @endif
                                    <i class="bi bi-chevron-right user-item-arrow" aria-hidden="true"></i>
                                </a>
                                @if ($authUser->esAdmin())
                                    <a href="{{ route('admin.PanelAdministrativo') }}" class="user-item">
                                        <span class="user-item-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                                        <span class="user-item-copy">
                                            <strong>Panel administrativo</strong>
                                            <small>Controla el portal</small>
                                        </span>
                                        <i class="bi bi-chevron-right user-item-arrow" aria-hidden="true"></i>
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="user-item">
                                    <span class="user-item-icon"><i class="bi bi-person-gear" aria-hidden="true"></i></span>
                                    <span class="user-item-copy">
                                        <strong>Ver y editar perfil</strong>
                                        <small>Actualiza tus datos</small>
                                    </span>
                                    <i class="bi bi-chevron-right user-item-arrow" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('profile.verificacion.edit') }}" class="user-item">
                                    <span class="user-item-icon"><i class="bi bi-patch-check" aria-hidden="true"></i></span>
                                    <span class="user-item-copy">
                                        <strong>Verificar perfil</strong>
                                        <small>Sube DNI frontal y reverso</small>
                                    </span>
                                    <i class="bi bi-chevron-right user-item-arrow" aria-hidden="true"></i>
                                </a>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="logout-inline">
                                @csrf
                                <button type="submit" class="user-item user-item-logout">
                                    <span class="user-item-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                                    <span class="user-item-copy">
                                        <strong>Cerrar sesion</strong>
                                        <small>Salir de tu cuenta</small>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </details>
                    <a href="{{ route('propiedades.create') }}" class="btn btn-solid">Publica gratis</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Iniciar sesion</a>
                    <a href="{{ route('register') }}" class="btn btn-solid">Publica gratis</a>
                @endauth
            </div>
            <details class="client-mobile-nav">
                <summary class="client-mobile-summary" aria-label="Abrir menu">
                    <span class="client-mobile-glyph">&#9776;</span>
                </summary>
                <div class="client-mobile-dropdown">
                    @auth
                        <div class="client-mobile-user">
                            <span class="client-mobile-avatar">
                                @if ($authUser->tieneFotoPerfil())
                                    <img src="{{ route('profile.photo', ['v' => optional($authUser->updated_at)->timestamp]) }}" alt="Foto">
                                @else
                                    {{ $userInitial }}
                                @endif
                            </span>
                            <div class="client-mobile-copy">
                                <p class="client-mobile-name">{{ $userFullName }}</p>
                                <p class="client-mobile-email">{{ $authUser->email }}</p>
                            </div>



                        </div>

                        <a href="{{ route('home', ['favoritos' => 1]) }}#props" class="client-mobile-link">
                            <i class="bi bi-heart"></i>
                            <span>Mis favoritos</span>
                        </a>
                        <a href="{{ route('propiedades.mine') }}" class="client-mobile-link {{ $isMineRoute ? 'is-active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <span>Mis publicaciones</span>
                        </a>

                        <a href="{{ route('propiedades.solicitudes') }}" class="client-mobile-link {{ $isSolicitudesRoute ? 'is-active' : '' }}">
                            <i class="bi bi-chat-left-text"></i>
                            <span>Solicitudes recibidas</span>
                            @if ($solicitudesRecibidasCount > 0)
                                <span class="mobile-alert-badge">
                                    {{ $solicitudesRecibidasCount > 99 ? '99+' : $solicitudesRecibidasCount }}
                                </span>
                            @endif
                        </a>
                        @if ($authUser->esAdmin())
                            <a href="{{ route('admin.PanelAdministrativo') }}" class="client-mobile-link">
                                <i class="bi bi-speedometer2"></i>
                                <span>Panel administrativo</span>
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="client-mobile-link">
                            <i class="bi bi-person-gear"></i>
                            <span>Ver y editar perfil</span>
                        </a>
                        <a href="{{ route('profile.verificacion.edit') }}" class="client-mobile-link">
                            <i class="bi bi-patch-check"></i>
                            <span>Verificar perfil</span>
                        </a>
                        <a href="{{ route('propiedades.create') }}" class="client-mobile-link">
                            <i class="bi bi-megaphone"></i>
                            <span>Publica gratis</span>
                        </a>
                        <a href="{{ route('portal.como-publicar') }}" class="client-mobile-link">
                            <i class="bi bi-journal-text"></i>
                            <span>Como publicar</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="client-mobile-form">
                            @csrf
                            <button type="submit" class="client-mobile-link">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar sesion</span>
                            </button>
                        </form>



                    @else
                        <a href="{{ route('login') }}" class="client-mobile-link">Iniciar sesion</a>
                        <a href="{{ route('register') }}" class="client-mobile-link">Crear cuenta</a>
                        <a href="{{ route('register') }}" class="client-mobile-link">Publica gratis</a>
                    @endauth
                </div>
            </details>
        </div>
    </header>

    <!-- BODY: estilos en resources/css/app.css y resources/css/pages/*.css -->
    <main class="client-main">
        @yield('content')
    </main>
    <!-- FOOTER: estilos en resources/css/app.css y resources/css/layouts/client.css -->
    <footer class="client-footer">
        <div class="client-footer-wrap">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand">
                    <span class="brand-icon">&#127807;</span>Kusay<span class="dot">.</span><span class="pe">pe</span>
                </a>
                <p class="footer-copy">
                    Plataforma inmobiliaria para comprar, vender y alquilar propiedades en la selva y sierra del Peru.
                </p>
                <div class="footer-cta-row">
                    <a href="{{ route('propiedades.create') }}" class="footer-cta">Publicar propiedad</a>
                    <a href="mailto:contacto@kusay.pe" class="footer-cta footer-cta-soft">kusaycontacto@gmail.com</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navegacion</h4>
                <a href="{{ route('home') }}#nuevaspropiedades">Propiedades</a>
                <a href="{{ route('home') }}">Inicio</a>
                <a href="{{ route('portal.como-publicar') }}">Como publicar</a>
                <a href="{{ route('home') }}">Destacadas</a>
            </div>

            <div class="footer-col">
                <h4>Cuenta</h4>
                @auth
                    <a href="{{ route('propiedades.mine') }}">Mis publicaciones</a>
                    <a href="{{ route('propiedades.solicitudes') }}">Solicitudes recibidas</a>
                    <a href="{{ route('profile.edit') }}">Mi perfil</a>
                    <a href="{{ route('home', ['favoritos' => 1]) }}#props">Mis favoritos</a>
                @else
                    <a href="{{ route('login') }}">Iniciar sesion</a>
                    <a href="{{ route('register') }}">Crear cuenta</a>
                    <a href="{{ route('register') }}">Publica gratis</a>
                @endauth
            </div>

            <div class="footer-col">
                <h4>Soporte</h4>
                <a href="{{ route('soporte.index') }}">Soporte</a>
                <a href="{{ route('soporte.ayuda') }}">Centro de ayuda</a>
                <a href="{{ route('soporte.terminos') }}">Terminos y condiciones</a>
                <a href="{{ route('soporte.privacidad') }}">Politica de privacidad</a>
                <a href="{{ route('soporte.legales') }}">Terminos legales</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ now()->year }} Kusay.pe. Todos los derechos reservados.</p>
            <p>Portal inmobiliario de la selva y sierra peruana.</p>
            <p class="footer-credit">
                <span>Desarrollado por</span>
                <a href="https://github.com/ivanC001" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-github" aria-hidden="true"></i>
                    <span>Ivan Calderon</span>
                </a>
            </p>
        </div>
    </footer>

    <script>
        (function () {
            var links = document.querySelectorAll('.client-links .nav-hash-link');
            if (!links.length) return;

            var setActive = function () {
                links.forEach(function (link) { link.classList.remove('is-active'); });

                var hash = (window.location.hash || '').replace('#', '');
                if (hash) {
                    var matched = Array.prototype.find.call(links, function (link) {
                        return link.dataset.navTarget === hash;
                    });
                    if (matched) {
                        matched.classList.add('is-active');
                        return;
                    }
                }

                var fallback = document.querySelector('.client-links .home-default');
                if (fallback) fallback.classList.add('is-active');
            };

            window.addEventListener('hashchange', setActive);
            setActive();
        })();
    </script>
    @yield('scripts')
</body>
</html>


