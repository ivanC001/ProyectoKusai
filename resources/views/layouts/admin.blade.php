<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Kusay.pe')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/png kusay.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/image/png kusay.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin-app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Panel</a>
                <a href="{{ route('admin.PanelAdministrativo') }}" class="admin-link {{ request()->routeIs('admin.PanelAdministrativo') || request()->routeIs('admin.PanelAdministrativo.tipos.*') ? 'active' : '' }}">Administrar tipo de terrenos</a>
                <a href="{{ route('admin.PanelAdministrativo.soporte') }}" class="admin-link {{ request()->routeIs('admin.PanelAdministrativo.soporte') || request()->routeIs('admin.PanelAdministrativo.soporte.update') ? 'active' : '' }}">Administrar terminos</a>
                <a href="{{ route('admin.PanelAdministrativo.sugerencias.index') }}" class="admin-link {{ request()->routeIs('admin.PanelAdministrativo.sugerencias.*') ? 'active' : '' }}">Ver sugerencias</a>
                <a href="{{ route('admin.PanelAdministrativo.usuarios.index') }}" class="admin-link {{ request()->routeIs('admin.PanelAdministrativo.usuarios.*') ? 'active' : '' }}">Administrar usuarios</a>
                <a href="{{ route('admin.verificaciones-usuarios.index') }}" class="admin-link {{ request()->routeIs('admin.verificaciones-usuarios.*') ? 'active' : '' }}">Verificacion de usuarios</a>
                <a href="/" class="admin-link">Volver al sitio</a>
            </nav>
        </aside>

        <div class="admin-content">
            <header class="admin-topbar">
                <h1>@yield('page_title', 'Administracion')</h1>
                <small>@yield('page_subtitle', 'Gestion interna del portal')</small>
            </header>

            <main class="admin-main">
                @yield('content')
            </main>

            <footer class="admin-footer">
                Panel administrativo Kusay.pe
            </footer>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
