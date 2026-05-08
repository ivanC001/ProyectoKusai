@extends('layouts.client')

@section('title', 'Mi perfil | Kusay.pe')

@section('styles')
<style>
    .profile-wrap {
        width: min(980px, 94vw);
        margin: 0 auto;
        display: grid;
        gap: 14px;
    }

    .profile-head {
        background: linear-gradient(140deg, #134a34, #1f6e4a);
        color: #ebfff4;
        border-radius: 16px;
        padding: 18px;
        display: grid;
        grid-template-columns: 88px 1fr;
        gap: 14px;
        align-items: center;
    }

    .profile-photo {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, .35);
        background: #d8ebe1;
    }

    .profile-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, .35);
        display: grid;
        place-items: center;
        font-size: 1.5rem;
        font-weight: 800;
        background: rgba(255, 255, 255, .2);
    }

    .profile-photo-picker {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, .45);
        display: block;
        cursor: pointer;
        overflow: hidden;
        position: relative;
        background: rgba(255, 255, 255, .12);
    }

    .profile-photo-picker .profile-photo,
    .profile-photo-picker .profile-avatar {
        width: 100%;
        height: 100%;
        border: none;
    }

    .camera-badge-head {
        position: absolute;
        right: 4px;
        bottom: 4px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: rgba(11, 44, 68, .92);
        border: 1px solid rgba(255, 255, 255, .5);
        font-size: .78rem;
    }

    .profile-head h1 {
        margin: 0;
        font-family: "Fraunces", serif;
        font-size: 1.7rem;
    }

    .profile-meta {
        margin-top: 4px;
        color: rgba(235, 255, 244, .86);
        font-size: .93rem;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .card {
        background: #fff;
        border: 1px solid #d4e1d9;
        border-radius: 14px;
        padding: 16px;
    }

    .card h2 {
        margin: 0 0 6px;
        color: #14402f;
        font-size: 1.14rem;
        font-weight: 800;
    }

    .card p {
        margin: 0 0 12px;
        color: #5f7f71;
        font-size: .92rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 11px;
    }

    .full {
        grid-column: 1 / -1;
    }

    .field label {
        display: inline-block;
        margin-bottom: 6px;
        font-size: .81rem;
        font-weight: 800;
        color: #305848;
        text-transform: uppercase;
    }

    .input,
    .textarea {
        width: 100%;
        border: 1px solid #d4e1d9;
        border-radius: 10px;
        padding: 10px 11px;
        font: inherit;
        color: #173e2f;
        background: #fff;
    }
    .input-readonly {
        background: #f3f7f4;
        color: #557465;
        cursor: not-allowed;
    }

    .textarea {
        min-height: 90px;
        resize: vertical;
    }

    .type-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 6px 12px;
        border: 1px solid #b8cdc1;
        color: #2f5b49;
        background: #f7fbf9;
        font-size: .82rem;
        font-weight: 700;
    }

    .actions {
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 10px 14px;
        font: inherit;
        font-size: .92rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-main {
        background: linear-gradient(130deg, #2d8f5f, #17573c);
        color: #fff;
    }

    .btn-outline {
        border-color: #b7cbbf;
        background: #fff;
        color: #335e4c;
        text-decoration: none;
    }

    .btn-danger {
        border-color: #d8b2b2;
        background: #fff5f5;
        color: #8c2d2d;
    }

    .message {
        border-radius: 10px;
        padding: 10px 11px;
        margin-bottom: 10px;
        font-size: .9rem;
    }

    .ok {
        border: 1px solid #9dcdb6;
        background: #edf8f2;
        color: #14613f;
    }

    .error {
        border: 1px solid #efc1c1;
        background: #fff1f1;
        color: #b34242;
    }

    .error ul {
        margin: 0;
        padding-left: 18px;
    }

    .photo-note {
        color: #547466;
        font-size: .85rem;
        display: grid;
        gap: 2px;
    }

    .input-file-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
        padding: 0;
        margin: -1px;
    }

    .logout-form {
        margin: 0;
    }

    @media (max-width: 820px) {
        .profile-head {
            grid-template-columns: 1fr;
            text-align: center;
            justify-items: center;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-wrap">
    <section class="profile-head">
        <label for="foto_perfil" class="profile-photo-picker" title="Cambiar foto de perfil">
            @if ($user->tieneFotoPerfil())
                <img
                    id="foto-preview-top"
                    class="profile-photo"
                    src="{{ route('profile.photo', ['v' => optional($user->updated_at)->timestamp]) }}"
                    alt="Foto de perfil"
                >
                <div id="foto-preview-top-avatar" class="profile-avatar" style="display:none;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @else
                <div id="foto-preview-top-avatar" class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <img id="foto-preview-top" class="profile-photo" alt="Vista previa de foto de perfil" style="display:none;">
            @endif
            <span class="camera-badge-head">&#128247;</span>
        </label>

        <div>
            <h1>{{ $user->name }} {{ $user->apellidos }}</h1>
            <div class="profile-meta">{{ $user->email }} | {{ $user->telefono ?: 'Sin telefono' }}</div>
            <div style="margin-top:8px;">
                <span class="type-chip">{{ $user->tipo_persona === 'empresa' ? 'Empresa' : 'Persona natural' }}</span>
            </div>
        </div>
    </section>

    <section class="profile-grid">
        <article class="card">
            <h2>Datos del perfil</h2>
            <p>Actualiza tus datos para mejorar la confianza de los compradores.</p>

            @if (session('status') === 'profile-updated')
                <div class="message ok">Perfil actualizado correctamente.</div>
            @endif
            @if (session('profile_dni_required'))
                <div class="message error">{{ session('profile_dni_required') }}</div>
            @endif

            @if ($errors->any())
                <div class="message error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nombres</label>
                        <input class="input" id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="field">
                        <label for="apellidos">Apellidos</label>
                        <input class="input" id="apellidos" type="text" name="apellidos" value="{{ old('apellidos', $user->apellidos) }}">
                    </div>

                    <div class="field">
                        <label for="email_readonly">Correo (no editable)</label>
                        <input class="input input-readonly" id="email_readonly" type="email" value="{{ $user->email }}" readonly>
                    </div>

                    @if (empty($user->dni))
                        <div class="field">
                            <label for="dni">DNI (obligatorio)</label>
                            <input class="input" id="dni" type="text" name="dni" value="{{ old('dni', $user->dni) }}" maxlength="20" required>
                        </div>
                    @else
                        <div class="field">
                            <label for="dni_readonly">DNI (no editable)</label>
                            <input class="input input-readonly" id="dni_readonly" type="text" value="{{ $user->dni }}" readonly>
                        </div>
                    @endif

                    <div class="field">
                        <label for="telefono">Telefono</label>
                        <input class="input" id="telefono" type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}">
                    </div>

                    <div class="field">
                        <label for="whatsapp">WhatsApp</label>
                        <input class="input" id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}">
                    </div>

                    <div class="field">
                        <label for="direccion">Direccion</label>
                        <input class="input" id="direccion" type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}">
                    </div>

                    <div class="field">
                        <label for="empresa">Razon social</label>
                        <input class="input" id="empresa" type="text" name="empresa" value="{{ old('empresa', $user->empresa) }}">
                    </div>

                    <div class="field">
                        <label for="nombre_comercial">Nombre comercial</label>
                        <input class="input" id="nombre_comercial" type="text" name="nombre_comercial" value="{{ old('nombre_comercial', $user->nombre_comercial) }}">
                    </div>

                    <div class="field full">
                        <label for="descripcion">Descripcion</label>
                        <textarea class="textarea" id="descripcion" name="descripcion">{{ old('descripcion', $user->descripcion) }}</textarea>
                    </div>

                    <div class="field full">
                        <label for="foto_perfil">Foto de perfil</label>
                        <div class="photo-note">
                            <span>Haz clic en la foto de la cabecera para cambiarla.</span>
                            <span>Luego presiona "Guardar cambios" para aplicar.</span>
                            <span>Formatos: JPG, JPEG, PNG, WEBP. Maximo: 4 MB.</span>
                        </div>
                        <input class="input-file-hidden" id="foto_perfil" type="file" name="foto_perfil" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                </div>

                <div class="actions" style="display: flex; justify-content: flex-end;">
                    <!-- <a href="{{ route('propiedades.create') }}" class="btn btn-outline">Publica gratis</a> -->
                    <button class="btn btn-main" type="submit">Guardar cambios</button>
                </div>
            </form>
        </article>

        <article class="card">
            <h2>Seguridad</h2>
            <p>Actualiza tu contrasena cuando lo necesites.</p>

            @if (session('status') === 'password-updated')
                <div class="message ok">Contrasena actualizada correctamente.</div>
            @endif

            @if ($errors->updatePassword->any())
                <div class="message error">
                    <ul>
                        @foreach ($errors->updatePassword->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="field full">
                        <label for="current_password">Contrasena actual</label>
                        <input class="input" id="current_password" type="password" name="current_password" autocomplete="current-password">
                    </div>
                    <div class="field">
                        <label for="password">Nueva contrasena</label>
                        <input class="input" id="password" type="password" name="password" autocomplete="new-password">
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Confirmar contrasena</label>
                        <input class="input" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>

                <div class="actions" style="display: flex; justify-content: flex-end;">
    <button class="btn btn-main" type="submit">Actualizar contraseña</button>
</div>
            </form>

            <!-- <div class="actions" style="margin-top: 10px;">
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button class="btn btn-danger" type="submit">Cerrar sesion</button>
                </form>
            </div> -->
        </article>
    </section>
</div>
@endsection

@section('scripts')
<script>
    (() => {
        const input = document.getElementById('foto_perfil');
        const previewTop = document.getElementById('foto-preview-top');
        const avatarTop = document.getElementById('foto-preview-top-avatar');

        if (!input || !previewTop) {
            return;
        }

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                if (!event.target || typeof event.target.result !== 'string') {
                    return;
                }

                previewTop.src = event.target.result;
                previewTop.style.display = 'block';

                if (avatarTop) {
                    avatarTop.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
@endsection
