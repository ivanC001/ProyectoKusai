@extends('layouts.client')

@section('title', 'Verificar perfil | Kusay.pe')

@section('content')
<section class="sec" style="max-width:980px;margin:0 auto;">
    <div class="card" style="border:1px solid #d4e1d9;border-radius:16px;padding:20px;background:#fff;">
        <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center;">
            <div>
                <p style="margin:0;color:#3d6a57;font-weight:800;text-transform:uppercase;font-size:.8rem;letter-spacing:.08em;">Confianza</p>
                <h1 style="margin:4px 0 0;color:#123d2d;font-family:'Fraunces',serif;font-size:2rem;">Verificar perfil</h1>
                <p style="margin:8px 0 0;color:#547466;">Envía tu DNI por ambos lados para activar el sello de usuario verificado.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline">Volver al perfil</a>
        </div>

        @if (session('success'))
            <div style="margin-top:14px;border:1px solid #9dcdb6;background:#edf8f2;color:#14613f;padding:10px 12px;border-radius:10px;font-weight:700;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin-top:14px;border:1px solid #efc1c1;background:#fff1f1;color:#b34242;padding:10px 12px;border-radius:10px;">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $estado = $verificacion?->estado ?? null;
            $perfilAprobado = $verificacion?->estaAprobada() ?? false;
        @endphp

        @if ($verificacion)
            <div style="margin-top:14px;padding:12px;border-radius:12px;border:1px solid #d4e1d9;background:#f7fbf9;">
                <p style="margin:0;font-weight:800;color:#24543f;">Estado actual:
                    @if ($estado === 'aprobado')
                        <span style="color:#1f7a4f;">Aprobado</span>
                    @elseif ($estado === 'rechazado')
                        <span style="color:#a13a3a;">Rechazado</span>
                    @else
                        <span style="color:#9a6a16;">Pendiente</span>
                    @endif
                </p>
                @if ($verificacion->observaciones)
                    <p style="margin:6px 0 0;color:#5c7b6d;">Observación admin: {{ $verificacion->observaciones }}</p>
                @endif
            </div>
        @endif

        @if ($perfilAprobado)
            <div style="margin-top:16px;border:1px solid #9dcdb6;background:#edf8f2;color:#14613f;padding:14px 16px;border-radius:12px;">
                <p style="margin:0;font-weight:800;">Tu usuario ya está verificado por Kusay.</p>
                <p style="margin:6px 0 0;color:#2a6248;">
                    Ya no necesitas volver a enviar fotos de DNI. Tu perfil muestra el sello de confianza en tus publicaciones.
                </p>
            </div>
        @else
            <form method="POST" action="{{ route('profile.verificacion.store') }}" enctype="multipart/form-data" style="margin-top:16px;display:grid;gap:12px;">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label for="dni" style="display:block;margin-bottom:6px;font-size:.81rem;font-weight:800;color:#305848;text-transform:uppercase;">DNI</label>
                        <input id="dni" name="dni" type="text" value="{{ old('dni', $user->dni) }}" required style="width:100%;border:1px solid #d4e1d9;border-radius:10px;padding:10px 11px;">
                    </div>
                    <div style="align-self:end;color:#5c7b6d;font-size:.9rem;">
                        Asegúrate de que el DNI coincida con tu cuenta.
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div>
                        <label for="dni_frontal" style="display:block;margin-bottom:6px;font-size:.81rem;font-weight:800;color:#305848;text-transform:uppercase;">DNI frontal</label>
                        <input id="dni_frontal" name="dni_frontal" type="file" accept=".jpg,.jpeg,.png,.webp" required style="width:100%;border:1px solid #d4e1d9;border-radius:10px;padding:10px 11px;background:#fff;">
                    </div>
                    <div>
                        <label for="dni_reverso" style="display:block;margin-bottom:6px;font-size:.81rem;font-weight:800;color:#305848;text-transform:uppercase;">DNI reverso</label>
                        <input id="dni_reverso" name="dni_reverso" type="file" accept=".jpg,.jpeg,.png,.webp" required style="width:100%;border:1px solid #d4e1d9;border-radius:10px;padding:10px 11px;background:#fff;">
                    </div>
                </div>

                <label style="display:flex;gap:8px;align-items:flex-start;color:#345d4d;font-size:.92rem;">
                    <input type="checkbox" name="confirmo_datos" value="1" required style="margin-top:2px;">
                    <span>Confirmo que mis datos son reales y autorizo la revisión para obtener el sello de usuario verificado.</span>
                </label>

                <div style="display:flex;justify-content:flex-end;">
                    <button class="btn btn-main" type="submit">Enviar solicitud de verificación</button>
                </div>
            </form>
        @endif
    </div>
</section>
@endsection
