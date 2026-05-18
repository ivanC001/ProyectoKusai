<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar correo | Kusay.pe</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/kusay-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/image/kusay-icon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(150deg, #0b2f21 0%, #176246 55%, #2f8c61 100%);
        }
        .verify-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 16px;
        }
        .verify-card {
            width: min(560px, 100%);
            border: 1px solid #dce7e1;
            border-radius: 18px;
            box-shadow: 0 24px 40px rgba(7, 28, 20, .22);
        }
        .brand-title {
            color: #114833;
            font-weight: 800;
            letter-spacing: .01em;
        }
        .code-input {
            font-size: 1.25rem;
            letter-spacing: .35rem;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="verify-shell">
        <section class="card verify-card">
            <div class="card-body p-4 p-md-5">
                <p class="text-uppercase fw-bold text-success mb-2">Verificacion de cuenta</p>
                <h1 class="h3 brand-title mb-3">Confirma tu correo con codigo</h1>
                <p class="text-secondary mb-3">
                    Enviamos un codigo de 6 digitos a
                    <span class="fw-semibold text-dark">{{ $maskedEmail ?? auth()->user()->email }}</span>.
                </p>

                @if ($codeExpiresAt)
                    <div class="alert alert-info py-2 small" role="alert">
                        El codigo actual vence: <strong>{{ $codeExpiresAt->format('d/m/Y H:i') }}</strong>
                    </div>
                @endif

                @if (session('status') === 'verification-code-sent' || session('status') === 'verification-link-sent')
                    <div class="alert alert-success py-2" role="alert">
                        Te enviamos un nuevo codigo de verificacion.
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning py-2" role="alert">
                        {{ session('warning') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger py-2" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.code.verify') }}" class="mb-3">
                    @csrf
                    <label for="verification_code" class="form-label fw-semibold">Codigo de verificacion</label>
                    <input
                        id="verification_code"
                        name="verification_code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        maxlength="6"
                        autocomplete="one-time-code"
                        class="form-control form-control-lg code-input @error('verification_code') is-invalid @enderror"
                        placeholder="000000"
                        value="{{ old('verification_code') }}"
                        required
                    >
                    <div class="form-text">Ingresa solo numeros, sin espacios.</div>
                    <button type="submit" class="btn btn-success w-100 mt-3">Verificar codigo</button>
                </form>

                <div class="d-flex flex-column flex-md-row gap-2">
                    <form method="POST" action="{{ route('verification.send') }}" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">Reenviar codigo</button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100">Cerrar sesion</button>
                    </form>
                </div>

                <a href="{{ url('/') }}" class="btn btn-link text-decoration-none px-0 mt-3">Volver al inicio</a>
            </div>
        </section>
    </main>
    <script>
        const codeInput = document.getElementById('verification_code');
        if (codeInput) {
            codeInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });
        }
    </script>
</body>
</html>

