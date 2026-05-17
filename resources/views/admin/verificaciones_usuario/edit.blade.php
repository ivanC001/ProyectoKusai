@extends('layouts.admin')

@section('title', 'Revisar verificacion de usuario | Admin')
@section('page_title', 'Verificacion de usuario')
@section('page_subtitle', trim(($verificacion->usuario?->name ?? '').' '.($verificacion->usuario?->apellidos ?? '')))

@section('content')
    @php
        $usuario = $verificacion->usuario;
        $nombreUsuario = trim((string) ($usuario?->name ?? '').' '.(string) ($usuario?->apellidos ?? ''));
        $nombreVerificador = trim((string) ($verificacion->verificador?->name ?? '').' '.(string) ($verificacion->verificador?->apellidos ?? ''));
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a
                href="{{ route('admin.verificaciones-usuarios.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700"
            >
                ← Volver al listado
            </a>

            @if ($verificacion->estaAprobada())
                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-extrabold text-emerald-700">
                    ✅ Usuario verificado
                </span>
            @elseif ($verificacion->estado === 'rechazado')
                <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-extrabold text-rose-700">
                    ❌ Solicitud rechazada
                </span>
            @else
                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-extrabold text-amber-700">
                    ⏳ Pendiente de revision
                </span>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-700">Datos enviados por el usuario</p>
            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">Usuario</p>
                    <p class="mt-1 text-base font-bold text-slate-900">{{ $nombreUsuario !== '' ? $nombreUsuario : 'Sin nombre' }}</p>
                    <p class="text-sm text-slate-600">{{ $usuario?->email ?? 'Sin correo' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500">DNI declarado</p>
                    <p class="mt-1 text-base font-bold text-slate-900">{{ $verificacion->dni }}</p>
                    <p class="text-sm text-slate-600">Telefono: {{ $usuario?->telefono ?: 'No indicado' }}</p>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-600">DNI frontal</p>
                <img
                    class="mt-3 w-full rounded-2xl border border-slate-200 object-cover"
                    src="{{ asset('storage/'.$verificacion->dni_frontal_path) }}"
                    alt="DNI frontal de {{ $nombreUsuario }}"
                >
            </article>
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-600">DNI reverso</p>
                <img
                    class="mt-3 w-full rounded-2xl border border-slate-200 object-cover"
                    src="{{ asset('storage/'.$verificacion->dni_reverso_path) }}"
                    alt="DNI reverso de {{ $nombreUsuario }}"
                >
            </article>
        </section>

        <form method="POST" action="{{ route('admin.verificaciones-usuarios.update', $verificacion) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                        <i class="bi bi-card-checklist text-xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">DNI legible</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Frontal y reverso son claros y se leen correctamente.</p>
                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="dni_legible" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old('dni_legible', $verificacion->dni_legible))>
                        <span class="text-sm font-bold text-slate-700">Marcar como validado</span>
                    </label>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                        <i class="bi bi-person-vcard-fill text-xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Datos coinciden</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Nombre, DNI y datos de cuenta coinciden con la evidencia.</p>
                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="datos_coinciden" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old('datos_coinciden', $verificacion->datos_coinciden))>
                        <span class="text-sm font-bold text-slate-700">Marcar como confirmado</span>
                    </label>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                        <i class="bi bi-telephone-forward-fill text-xl" aria-hidden="true"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Contacto validado</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Telefono/correo de la cuenta son reales y utilizables.</p>
                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="contacto_validado" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old('contacto_validado', $verificacion->contacto_validado))>
                        <span class="text-sm font-bold text-slate-700">Marcar como validado</span>
                    </label>
                </article>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="estado" class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-600">Estado de la solicitud</label>
                        <select id="estado" name="estado" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none">
                            <option value="pendiente" @selected(old('estado', $verificacion->estado) === 'pendiente')>Pendiente</option>
                            <option value="aprobado" @selected(old('estado', $verificacion->estado) === 'aprobado')>Aprobado</option>
                            <option value="rechazado" @selected(old('estado', $verificacion->estado) === 'rechazado')>Rechazado</option>
                        </select>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-600">Ultima verificacion</p>
                        @if ($verificacion->fecha_verificacion)
                            <p class="mt-2 text-sm font-bold text-slate-800">{{ $verificacion->fecha_verificacion->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-slate-500">{{ $nombreVerificador !== '' ? $nombreVerificador : 'Administrador' }}</p>
                        @else
                            <p class="mt-2 text-sm text-slate-500">Aun no se confirma una aprobacion final.</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <label for="observaciones" class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-600">Observaciones internas</label>
                    <textarea id="observaciones" name="observaciones" rows="4" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-emerald-500 focus:outline-none" placeholder="Comentarios internos sobre la revision...">{{ old('observaciones', $verificacion->observaciones) }}</textarea>
                </div>
            </section>

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                    Revisa los campos antes de guardar la revision.
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a
                    href="{{ route('admin.verificaciones-usuarios.index') }}"
                    class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-slate-400"
                >
                    Cancelar
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-800"
                >
                    Guardar revision
                </button>
            </div>
        </form>
    </div>
@endsection

