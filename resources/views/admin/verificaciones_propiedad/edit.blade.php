@extends('layouts.admin')

@section('title', 'Editar verificacion | Admin')
@section('page_title', 'Verificacion de propiedad')
@section('page_subtitle', $propiedad->titulo)

@section('content')
    @php
        $ubicacion = collect([
            $propiedad->ubicacion?->distrito,
            $propiedad->ubicacion?->provincia,
            $propiedad->ubicacion?->departamento,
        ])->filter()->implode(', ');
        $esCompleta = $verificacion->documentos_revisados && $verificacion->visita_confirmada && $verificacion->vendedor_identificado;
        $nombreVerificador = trim((string) ($verificacion->verificador?->name ?? '').' '.(string) ($verificacion->verificador?->apellidos ?? ''));
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a
                href="{{ route('admin.verificaciones-propiedad.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700"
            >
                ← Volver al listado
            </a>
            @if ($esCompleta)
                <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-extrabold text-emerald-700">
                    ✅ Verificado por Kusay
                </span>
            @else
                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-extrabold text-amber-700">
                    ⏳ Verificacion pendiente
                </span>
            @endif
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-700">Ficha inmobiliaria</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-900">{{ $propiedad->titulo }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $ubicacion !== '' ? $ubicacion : 'Sin ubicacion registrada' }}</p>
                    <p class="mt-1 text-sm text-slate-500">Tipo: {{ $propiedad->tipoPropiedad?->nombre ?? 'Sin tipo' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
                    <p class="font-bold text-slate-700">Ultima verificacion completa</p>
                    @if ($verificacion->fecha_verificacion)
                        <p class="mt-1 text-slate-600">{{ $verificacion->fecha_verificacion->format('Y-m-d H:i') }}</p>
                        <p class="text-slate-500">{{ $nombreVerificador !== '' ? $nombreVerificador : 'Administrador' }}</p>
                    @else
                        <p class="mt-1 text-slate-500">Aun no se registra una verificacion completa.</p>
                    @endif
                </div>
            </div>
        </section>

        <form method="POST" action="{{ route('admin.verificaciones-propiedad.update', $propiedad) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 3h6l4 4v14H5V3h4z"></path>
                            <path d="M9 3v5h6"></path>
                            <path d="M8.5 13h7"></path>
                            <path d="M8.5 17h7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Documentos revisados</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Valida SUNARP, titularidad y consistencia legal del inmueble.</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-extrabold text-sky-700">
                            📘 Checklist legal
                        </span>
                    </div>
                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="documentos_revisados" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old('documentos_revisados', $verificacion->documentos_revisados))>
                        <span class="text-sm font-bold text-slate-700">Marcar como revisado</span>
                    </label>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 11l9-8 9 8"></path>
                            <path d="M5 10v10h14V10"></path>
                            <path d="M9 14h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Visita confirmada</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Se confirma en campo que fotos, medidas y ubicacion son reales.</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-700">
                            🏡 Validacion fisica
                        </span>
                    </div>
                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="visita_confirmada" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old('visita_confirmada', $verificacion->visita_confirmada))>
                        <span class="text-sm font-bold text-slate-700">Marcar como confirmada</span>
                    </label>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"></path>
                            <path d="M4 21a8 8 0 0 1 16 0"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Vendedor identificado</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500">Confirma identidad del anunciante con DNI y datos de contacto vigentes.</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-extrabold text-indigo-700">
                            👤 Identidad validada
                        </span>
                    </div>
                    <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <input type="checkbox" name="vendedor_identificado" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" @checked(old('vendedor_identificado', $verificacion->vendedor_identificado))>
                        <span class="text-sm font-bold text-slate-700">Marcar como identificado</span>
                    </label>
                </article>
            </section>

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
                    Revisa los campos de verificacion antes de guardar.
                </div>
            @endif

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a
                    href="{{ route('admin.verificaciones-propiedad.index') }}"
                    class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 transition hover:border-slate-400"
                >
                    Cancelar
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-800"
                >
                    Guardar verificacion
                </button>
            </div>
        </form>
    </div>
@endsection

