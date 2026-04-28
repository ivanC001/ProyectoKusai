<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kusay.pe')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    @php
        $homeUrl = route('home');
    @endphp
    <header class="client-nav">
        <div class="client-nav-wrap">
            <!-- //logo de kusay -->
            <a href="/" class="brand"><span class="brand-icon">&#127807;</span>Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <ul class="client-links">
                <li><a href="{{ $homeUrl }}#propiedades">Propiedades</a></li>
                <li><a href="{{ $homeUrl }}#ciudades">Ciudades</a></li>
                <li><a href="{{ $homeUrl }}#publicar">Como publicar</a></li>
                <li><a href="{{ $homeUrl }}#destacadas">Destacadas</a></li>
            </ul>
            <div class="client-actions">
                @auth
                    <a href="{{ route('propiedades.mine') }}" class="btn btn-outline">Mis publicaciones</a>
                    <details class="user-menu">
                        <summary class="btn btn-outline user-summary">
                            <span class="user-avatar">
                                @if (auth()->user()->tieneFotoPerfil())
                                    <img src="{{ route('profile.photo', ['v' => optional(auth()->user()->updated_at)->timestamp]) }}" alt="Foto">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </span>
                            <span class="user-fullname">{{ trim(auth()->user()->name.' '.auth()->user()->apellidos) }}</span>
                            <span class="user-caret">&#9662;</span>
                        </summary>
                        <div class="user-dropdown">
                            <a href="{{ route('home', ['favoritos' => 1]) }}#props" class="user-item">Mis favoritos</a>
                            <a href="{{ route('propiedades.mine') }}" class="user-item">Mis publicaciones</a>
                            @if (auth()->user()->esAdmin())
                                <a href="{{ route('admin.PanelAdministrativo') }}" class="user-item">Panel administrativo</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="user-item">Ver y editar perfil</a>
                            <form method="POST" action="{{ route('logout') }}" class="logout-inline">
                                @csrf
                                <button type="submit" class="user-item">Cerrar sesion</button>
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
                                @if (auth()->user()->tieneFotoPerfil())
                                    <img src="{{ route('profile.photo', ['v' => optional(auth()->user()->updated_at)->timestamp]) }}" alt="Foto">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </span>
                            <div class="client-mobile-copy">
                                <p class="client-mobile-name">{{ trim(auth()->user()->name.' '.auth()->user()->apellidos) }}</p>
                                <p class="client-mobile-email">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('home', ['favoritos' => 1]) }}#props" class="client-mobile-link">Mis favoritos</a>
                        <a href="{{ route('propiedades.mine') }}" class="client-mobile-link">Mis publicaciones</a>
                        @if (auth()->user()->esAdmin())
                            <a href="{{ route('admin.PanelAdministrativo') }}" class="client-mobile-link">Panel administrativo</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="client-mobile-link">Ver y editar perfil</a>
                        <a href="{{ route('propiedades.create') }}" class="client-mobile-link">Publica gratis</a>
                        <form method="POST" action="{{ route('logout') }}" class="client-mobile-form">
                            @csrf
                            <button type="submit" class="client-mobile-link">Cerrar sesion</button>
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

    <main class="client-main">
        @yield('content')
    </main>
 <!-- pie de paginas de kusay -->
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
                    <a href="mailto:contacto@kusay.pe" class="footer-cta footer-cta-soft">contacto@kusay.pe</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navegacion</h4>
                <a href="{{ route('home') }}#propiedades">Propiedades</a>
                <a href="{{ route('home') }}#ciudades">Ciudades</a>
                <a href="{{ route('home') }}#publicar">Como publicar</a>
                <a href="{{ route('home') }}#destacadas">Destacadas</a>
            </div>

            <div class="footer-col">
                <h4>Cuenta</h4>
                @auth
                    <a href="{{ route('propiedades.mine') }}">Mis publicaciones</a>
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
        </div>
    </footer>

    @yield('scripts')
</body>
</html>

