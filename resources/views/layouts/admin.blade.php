<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin | Kusay.pe')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-bg: #edf3ef;
            --admin-paper: #ffffff;
            --admin-line: #cfdad3;
            --admin-ink: #163f30;
            --admin-soft: #5f7f71;
            --admin-brand: #124c35;
            --admin-brand-2: #1f8b58;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--admin-ink);
            background: var(--admin-bg);
        }
        a { color: inherit; text-decoration: none; }

        .admin-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 260px 1fr;
        }

        .admin-sidebar {
            background: linear-gradient(180deg, #0f3b2a, #102f22);
            color: #e5f2eb;
            border-right: 1px solid rgba(255, 255, 255, .08);
            padding: 20px 14px;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .admin-brand {
            font-family: "Fraunces", serif;
            font-size: 1.52rem;
            font-weight: 800;
            letter-spacing: -.4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 20px;
        }
        .admin-brand .dot { color: #d47f4b; }
        .admin-brand .pe { color: #7dd6a9; }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .admin-link {
            display: block;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 10px;
            padding: 10px 11px;
            color: #d4e7dd;
            font-weight: 700;
            font-size: .93rem;
            transition: .2s ease;
        }
        .admin-link:hover,
        .admin-link.active {
            background: rgba(125, 214, 169, .14);
            border-color: rgba(125, 214, 169, .45);
            color: #ffffff;
        }

        .admin-content {
            display: grid;
            grid-template-rows: auto 1fr auto;
            min-height: 100vh;
        }
        .admin-topbar {
            background: rgba(252, 255, 253, .92);
            border-bottom: 1px solid var(--admin-line);
            backdrop-filter: blur(10px);
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .admin-topbar h1 {
            margin: 0;
            font-size: 1.22rem;
            font-weight: 800;
        }
        .admin-topbar small {
            color: var(--admin-soft);
        }

        .admin-main {
            padding: 22px;
        }

        .admin-footer {
            border-top: 1px solid var(--admin-line);
            background: #ffffff;
            color: var(--admin-soft);
            font-size: .84rem;
            padding: 13px 22px;
        }

        @media (max-width: 980px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }
            .admin-sidebar {
                position: static;
                height: auto;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">Kusay<span class="dot">.</span><span class="pe">pe</span></a>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Panel</a>
                <a href="{{ route('admin.PanelAdministrativo') }}" class="admin-link {{ request()->routeIs('admin.PanelAdministrativo') || request()->routeIs('admin.PanelAdministrativo.tipos.*') ? 'active' : '' }}">Administrar tipo de terrenos</a>
                <a href="{{ route('admin.PanelAdministrativo.usuarios.index') }}" class="admin-link {{ request()->routeIs('admin.PanelAdministrativo.usuarios.*') ? 'active' : '' }}">Administrar usuarios</a>
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
