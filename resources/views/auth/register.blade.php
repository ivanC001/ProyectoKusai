<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/image/kusay-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/image/kusay-icon.png') }}">
    <title>Registro de usuario | Kusay.pe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #173e2f;
            --muted: #628372;
            --line: #d4e1d9;
            --paper: #ffffff;
            --bg: #f3f8f5;
            --brand-900: #103928;
            --brand-700: #1b6c49;
            --brand-500: #3fb173;
            --danger: #b34242;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: "Manrope", sans-serif;
            background:
                radial-gradient(circle at 8% 5%, rgba(95, 188, 133, .2), transparent 26%),
                radial-gradient(circle at 92% 88%, rgba(79, 163, 113, .18), transparent 33%),
                linear-gradient(160deg, #0f2e21 0%, #18583c 52%, #2f8358 100%);
            padding: 26px 14px;
        }

        .shell {
            width: min(1220px, 100%);
            margin: 0 auto;
            background: rgba(255, 255, 255, .96);
            border: 1px solid rgba(23, 62, 47, .14);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 26px 48px rgba(8, 30, 21, .32);
            display: grid;
            grid-template-columns: 1fr;
        }

        .topbar {
            padding: 18px 24px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            background: #fbfdfc;
        }

        .brand {
            text-decoration: none;
            color: #123e2e;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: "Fraunces", serif;
            font-size: 1.6rem;
            font-weight: 800;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chip-link {
            text-decoration: none;
            border: 1px solid #b9cfc2;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: .88rem;
            font-weight: 700;
            color: #345b4b;
            background: #fff;
        }

        .chip-link.main {
            background: linear-gradient(130deg, var(--brand-700), var(--brand-900));
            border-color: transparent;
            color: #fff;
        }

        .content {
            padding: 24px;
            background: var(--bg);
        }

        .hero {
            margin-bottom: 16px;
        }

        .hero h1 {
            margin: 0 0 6px;
            font-family: "Fraunces", serif;
            color: #10402f;
            font-size: clamp(1.7rem, 3.2vw, 2.4rem);
            line-height: 1.12;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            max-width: 780px;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin: 14px 0 24px;
        }

        .step {
            background: #f5f7f6;
            border: 1px solid #c8d8cf;
            border-radius: 16px;
            padding: 16px 16px 14px;
            min-height: 140px;
        }

        .step.active {
            background: #dfe9e4;
            border-color: #95bda9;
        }

        .step b {
            display: block;
            color: #0f5b3b;
            font-size: 1.12rem;
            line-height: 1;
            margin-bottom: 10px;
            font-weight: 800;
        }

        .step h3 {
            margin: 0 0 6px;
            font-size: 1.12rem;
            line-height: 1.15;
            color: #153f31;
            font-weight: 800;
        }

        .step p {
            margin: 0;
            color: #5f7f71;
            font-size: .82rem;
            line-height: 1.38;
        }

        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
        }

        .alert {
            border: 1px solid #efc1c1;
            background: #fff1f1;
            color: var(--danger);
            border-radius: 11px;
            padding: 11px 12px;
            margin-bottom: 12px;
            font-size: .9rem;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .switch {
            display: inline-flex;
            gap: 8px;
            background: #f4faf7;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 5px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .switch button {
            border: none;
            border-radius: 999px;
            padding: 7px 14px;
            font: inherit;
            font-size: .86rem;
            font-weight: 700;
            color: #456a5a;
            background: transparent;
            cursor: pointer;
        }

        .switch button.active {
            background: linear-gradient(130deg, var(--brand-700), var(--brand-900));
            color: #fff;
        }
        .tipo-hint {
            margin: -4px 0 12px;
            border: 1px solid #cfe0d6;
            background: #f7fbf9;
            border-radius: 10px;
            color: #476c5b;
            font-size: .82rem;
            font-weight: 700;
            padding: 8px 10px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .full {
            grid-column: 1 / -1;
        }

        .field label {
            display: inline-block;
            margin-bottom: 6px;
            font-size: .83rem;
            font-weight: 800;
            color: #2f5a49;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 11px;
            padding: 11px 12px;
            font: inherit;
            color: var(--ink);
            background: #fff;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            border-color: #5aa77e;
            box-shadow: 0 0 0 3px rgba(86, 166, 122, .18);
        }
        .required-hint {
            border: 1px solid #cfe0d6;
            background: #f6fbf8;
            border-radius: 10px;
            color: #466b5a;
            font-size: .84rem;
            font-weight: 700;
            padding: 9px 11px;
            margin-bottom: 12px;
        }
        .field-help {
            margin-top: 5px;
            color: #618173;
            font-size: .8rem;
            line-height: 1.35;
        }

        .terms {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: #496d5c;
            font-size: .9rem;
        }
        .terms-box {
            border: 1px solid #cfe0d6;
            border-radius: 12px;
            background: #f7fbf8;
            padding: 11px 12px;
        }
        .terms-check {
            margin-top: 2px;
        }
        .terms-copy {
            display: grid;
            gap: 4px;
        }
        .terms-copy strong {
            color: #164c37;
            font-size: .93rem;
            line-height: 1.35;
        }
        .terms-link {
            color: #166b45;
            font-size: .86rem;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .terms-link:hover {
            color: #0f5236;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .link {
            color: #1a6e49;
            font-weight: 700;
            text-decoration: none;
            font-size: .92rem;
        }

        .link:hover { text-decoration: underline; }

        .submit {
            border: none;
            border-radius: 11px;
            background: linear-gradient(130deg, var(--brand-700), var(--brand-900));
            color: #fff;
            font: inherit;
            font-weight: 800;
            font-size: .95rem;
            padding: 11px 16px;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(16, 57, 40, .22);
        }

        .submit:hover { filter: brightness(1.05); }

        .hidden {
            display: none;
        }

        @media (max-width: 950px) {
            .steps {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .topbar,
            .content {
                padding: 16px;
            }

            .grid,
            .steps {
                grid-template-columns: 1fr;
            }

            .actions {
                align-items: stretch;
            }

            .submit {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <a href="{{ url('/') }}" class="brand">Kusay.pe</a>
            <div class="top-actions">
                <a href="{{ route('login') }}" class="chip-link">Ya tengo cuenta</a>
                <a href="{{ route('register') }}" class="chip-link main">Publica gratis</a>
            </div>
        </header>

        <section class="content">
            <div class="hero">
                <h1>Crea tu cuenta para publicar propiedades</h1>
                <p>Completa tus datos basicos y empieza a publicar terrenos, casas, departamentos o lotes en minutos.</p>
            </div>

            <section class="steps">
                <article class="step active">
                    <b>1.</b>
                    <h3>Crea tu cuenta</h3>
                    <p>Registro rapido con correo y telefono.</p>
                </article>
                <article class="step">
                    <b>2.</b>
                    <h3>Sube fotos</h3>
                    <p>Publica imagenes claras de tu propiedad.</p>
                </article>
                <article class="step">
                    <b>3.</b>
                    <h3>Completa datos</h3>
                    <p>Precio, metraje, ubicacion y detalles.</p>
                </article>
                <article class="step">
                    <b>4.</b>
                    <h3>Publica y recibe contactos</h3>
                    <p>Conecta con compradores de forma directa.</p>
                </article>
            </section>

            <section class="card">
                @if ($errors->any())
                    <div class="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="required-hint">
                    Los campos obligatorios cambian segun el tipo de persona: DNI para persona natural y RUC para empresa.
                </div>

                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    <div class="switch">
                        <button type="button" data-tipo="natural" class="{{ old('tipo_persona', 'natural') === 'natural' ? 'active' : '' }}">Persona natural</button>
                        <button type="button" data-tipo="empresa" class="{{ old('tipo_persona') === 'empresa' ? 'active' : '' }}">Empresa</button>
                    </div>
                    <div id="tipo-hint" class="tipo-hint">Selecciona tu tipo de persona. El formulario mostrara campos segun corresponda.</div>

                    <input type="hidden" id="tipo_persona" name="tipo_persona" value="{{ old('tipo_persona', 'natural') }}">

                    <div class="grid">
                        <div class="field">
                            <label id="name-label" for="name">Nombres</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>

                        <div class="field js-natural">
                            <label id="apellidos-label" for="apellidos">Apellidos</label>
                            <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos') }}" autocomplete="family-name">
                        </div>

                        <div class="field js-natural">
                            <label for="dni">DNI</label>
                            <input id="dni" type="text" name="dni" value="{{ old('dni') }}" maxlength="20" inputmode="numeric">
                            <div class="field-help">Ingresa tu DNI sin espacios ni guiones.</div>
                        </div>

                        <div class="field js-empresa">
                            <label id="empresa-label" for="empresa">Razon social</label>
                            <input id="empresa" type="text" name="empresa" value="{{ old('empresa') }}">
                        </div>

                        <div class="field js-empresa">
                            <label for="nombre_comercial">Nombre comercial</label>
                            <input id="nombre_comercial" type="text" name="nombre_comercial" value="{{ old('nombre_comercial') }}">
                        </div>

                        <div class="field js-empresa">
                            <label for="ruc">RUC</label>
                            <input id="ruc" type="text" name="ruc" value="{{ old('ruc') }}" maxlength="20" inputmode="numeric">
                            <div class="field-help">Ingresa el RUC de la empresa para validar tu perfil comercial.</div>
                        </div>

                        <div class="field">
                            <label for="email">Correo</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                            <div class="field-help">Correos empresariales suelen corresponder a persona juridica.</div>
                        </div>

                        <div class="field">
                            <label for="telefono">Telefono</label>
                            <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}" required>
                        </div>

                        <div class="field">
                            <label for="whatsapp">WhatsApp (opcional)</label>
                            <input id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp') }}">
                        </div>

                        <div class="field">
                            <label for="password">Contrasena</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password">
                            <div class="field-help">Usa una contrasena segura. Recomendado: minimo 8 caracteres.</div>
                        </div>

                        <div class="field">
                            <label for="password_confirmation">Confirmar contrasena</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="field full">
                            <div class="terms-box">
                                <label class="terms" for="acepta_terminos">
                                    <input class="terms-check" id="acepta_terminos" type="checkbox" name="acepta_terminos" value="1" {{ old('acepta_terminos') ? 'checked' : '' }}>
                                    <span class="terms-copy">
                                        <strong>Acepto los terminos y condiciones del portal.</strong>
                                        <a class="terms-link" href="{{ route('soporte.terminos') }}" target="_blank" rel="noopener noreferrer">
                                            Leer terminos y condiciones
                                        </a>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="{{ route('login') }}" class="link">Ya tienes cuenta? Inicia sesion</a>
                        <button class="submit" type="submit">Crear cuenta</button>
                    </div>
                </form>
            </section>
        </section>
    </main>

    <script>
        const buttons = document.querySelectorAll('[data-tipo]');
        const tipoInput = document.getElementById('tipo_persona');
        const naturalFields = document.querySelectorAll('.js-natural');
        const empresaFields = document.querySelectorAll('.js-empresa');
        const dniInput = document.getElementById('dni');
        const rucInput = document.getElementById('ruc');
        const emailInput = document.getElementById('email');
        const tipoHint = document.getElementById('tipo-hint');
        const nameLabel = document.getElementById('name-label');
        const apellidosLabel = document.getElementById('apellidos-label');
        const empresaLabel = document.getElementById('empresa-label');
        let manualSelection = false;
        const personalDomains = [
            'gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'icloud.com',
            'live.com', 'msn.com', 'aol.com', 'proton.me', 'protonmail.com'
        ];

        const applyTipo = (tipo, reason = '') => {
            tipoInput.value = tipo;

            buttons.forEach((btn) => {
                btn.classList.toggle('active', btn.dataset.tipo === tipo);
            });

            naturalFields.forEach((el) => {
                el.classList.toggle('hidden', tipo !== 'natural');
            });

            empresaFields.forEach((el) => {
                el.classList.toggle('hidden', tipo !== 'empresa');
            });

            if (dniInput) {
                dniInput.required = tipo === 'natural';
            }

            if (rucInput) {
                rucInput.required = tipo === 'empresa';
            }

            if (nameLabel) {
                nameLabel.textContent = tipo === 'empresa' ? 'Nombre del representante' : 'Nombres';
            }
            if (apellidosLabel) {
                apellidosLabel.textContent = tipo === 'empresa' ? 'Apellidos del representante' : 'Apellidos';
            }
            if (empresaLabel) {
                empresaLabel.textContent = 'Razon social';
            }

            if (tipoHint) {
                if (reason !== '') {
                    tipoHint.textContent = reason;
                } else if (tipo === 'empresa') {
                    tipoHint.textContent = 'Persona juridica: completa razon social y RUC para validar la empresa.';
                } else {
                    tipoHint.textContent = 'Persona natural: completa nombres, apellidos y DNI.';
                }
            }
        };

        const detectTipoByInputs = () => {
            const dniValue = (dniInput?.value || '').trim();
            const rucValue = (rucInput?.value || '').trim();
            const emailValue = (emailInput?.value || '').trim().toLowerCase();

            if (rucValue !== '') {
                applyTipo('empresa', 'Detectamos RUC ingresado: cambiamos a persona juridica.');
                return;
            }

            if (dniValue !== '') {
                applyTipo('natural', 'Detectamos DNI ingresado: cambiamos a persona natural.');
                return;
            }

            if (manualSelection) {
                return;
            }

            if (emailValue.includes('@')) {
                const domain = emailValue.split('@')[1] || '';
                if (domain !== '' && !personalDomains.includes(domain)) {
                    applyTipo('empresa', 'Correo con dominio empresarial detectado. Puedes cambiarlo manualmente si eres persona natural.');
                } else if (domain !== '') {
                    applyTipo('natural', 'Correo personal detectado. Puedes cambiar a persona juridica si corresponde.');
                }
            }
        };

        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                manualSelection = true;
                applyTipo(btn.dataset.tipo, btn.dataset.tipo === 'empresa'
                    ? 'Seleccionaste persona juridica: completa razon social y RUC.'
                    : 'Seleccionaste persona natural: completa apellidos y DNI.'
                );
            });
        });

        emailInput?.addEventListener('blur', detectTipoByInputs);
        dniInput?.addEventListener('input', detectTipoByInputs);
        rucInput?.addEventListener('input', detectTipoByInputs);

        applyTipo(tipoInput.value || 'natural');
        detectTipoByInputs();
    </script>
</body>
</html>

