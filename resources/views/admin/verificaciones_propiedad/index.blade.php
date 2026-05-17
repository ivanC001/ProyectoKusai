@extends('layouts.admin')

@section('title', 'Verificado por Kusay | Admin')
@section('page_title', 'Verificado por Kusay')
@section('page_subtitle', 'Gestiona el sello de confianza en cada publicacion')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Propiedades</p>
                <p class="mt-2 text-3xl font-black text-slate-900">{{ number_format((int) $resumen['total'], 0, '.', ',') }}</p>
                <p class="mt-1 text-sm text-slate-500">Total de publicaciones registradas.</p>
            </article>
            <article class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700">Completamente verificadas</p>
                <p class="mt-2 text-3xl font-black text-emerald-900">{{ number_format((int) $resumen['completas'], 0, '.', ',') }}</p>
                <p class="mt-1 text-sm text-emerald-700">Cumplen los 3 criterios de verificacion.</p>
            </article>
            <article class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-amber-700">Pendientes</p>
                <p class="mt-2 text-3xl font-black text-amber-900">{{ number_format((int) $resumen['pendientes'], 0, '.', ',') }}</p>
                <p class="mt-1 text-sm text-amber-700">Requieren completar al menos un criterio.</p>
            </article>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Listado de verificaciones</h2>
                    <p class="text-sm text-slate-500">Selecciona una propiedad para actualizar su estado.</p>
                </div>
                <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-bold text-slate-600">
                    {{ number_format((int) $propiedades->total(), 0, '.', ',') }} registros
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-600">Propiedad</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-600">Ubicacion</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-600">Estado</th>
                            <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-600">Verificado por</th>
                            <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-[0.16em] text-slate-600">Accion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($propiedades as $propiedad)
                            @php
                                $verificacion = $propiedad->verificacion;
                                $completa = $propiedad->estaVerificadaPorKusay();
                                $nombreVerificador = trim((string) ($verificacion?->verificador?->name ?? '').' '.(string) ($verificacion?->verificador?->apellidos ?? ''));
                                $ubicacion = collect([
                                    $propiedad->ubicacion?->distrito,
                                    $propiedad->ubicacion?->provincia,
                                    $propiedad->ubicacion?->departamento,
                                ])->filter()->implode(', ');
                            @endphp
                            <tr class="align-top">
                                <td class="px-5 py-4">
                                    <p class="font-extrabold text-slate-900">{{ $propiedad->titulo }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $propiedad->tipoPropiedad?->nombre ?? 'Sin tipo' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-700">{{ $ubicacion !== '' ? $ubicacion : 'Sin ubicacion' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($completa)
                                        <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-700">
                                            ✅ Verificado por Kusay
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700">
                                            ⏳ Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($verificacion?->fecha_verificacion)
                                        <p class="text-sm font-bold text-slate-800">{{ $nombreVerificador !== '' ? $nombreVerificador : 'Administrador' }}</p>
                                        <p class="text-xs text-slate-500">{{ $verificacion->fecha_verificacion->format('Y-m-d H:i') }}</p>
                                    @else
                                        <p class="text-sm text-slate-500">Sin registro de verificacion final.</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a
                                        href="{{ route('admin.verificaciones-propiedad.edit', $propiedad) }}"
                                        class="inline-flex items-center rounded-xl border border-slate-300 px-3 py-2 text-sm font-extrabold text-slate-700 transition hover:border-emerald-500 hover:text-emerald-700"
                                    >
                                        Editar verificacion
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-sm font-semibold text-slate-500">
                                    No hay propiedades registradas para verificar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($propiedades->lastPage() > 1)
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $propiedades->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

