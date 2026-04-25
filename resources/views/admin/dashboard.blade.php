@extends('layouts.admin')

@section('title', 'Admin | Panel')
@section('page_title', 'Panel administrativo')
@section('page_subtitle', 'Resumen y accesos rapidos')

@section('styles')
<style>
    .grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    .card {
        background: #fff;
        border: 1px solid #cfdbd3;
        border-radius: 14px;
        padding: 18px;
    }
    .card h3 {
        margin: 0 0 6px;
        font-size: 1rem;
    }
    .card p {
        margin: 0;
        color: #5f7f71;
        font-size: .92rem;
    }
    .card a {
        display: inline-block;
        margin-top: 12px;
        font-weight: 700;
        color: #156e47;
    }
    @media (max-width: 980px) {
        .grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
    <section class="grid">
        <article class="card">
            <h3>Tipos de propiedad</h3>
            <p>Registra categorias como casa, lote, departamento, chacra u oficinas.</p>
            <a href="{{ route('admin.tipos-propiedad.index') }}">Gestionar tipos</a>
        </article>
        <article class="card">
            <h3>Registro de usuarios</h3>
            <p>Alta de cuentas de cliente o empresa para publicar en el portal.</p>
            <a href="{{ route('register') }}">Ir a registro</a>
        </article>
        <article class="card">
            <h3>Registro de propiedades</h3>
            <p>Publica nuevos inmuebles asignando usuario, tipo y ubicacion.</p>
            <a href="{{ route('propiedades.create') }}">Ir a propiedades</a>
        </article>
    </section>
@endsection
