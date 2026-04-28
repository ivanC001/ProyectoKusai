@extends('layouts.client')

@section('title', $title . ' | Kusay.pe')

@section('content')
<section class="support-page">
    <div class="support-wrap">
        <header class="support-header">
            <p class="support-tag">Soporte</p>
            <h1>{{ $payload['title'] ?? $title }}</h1>
            <p class="support-summary">{{ $payload['summary'] ?? '' }}</p>
            <p class="support-date">Actualizado: {{ $payload['updated_at'] ?? now()->toDateString() }}</p>
        </header>

        @foreach (($payload['sections'] ?? []) as $section)
            <article class="support-card">
                <h2>{{ $section['title'] ?? '' }}</h2>

                @foreach (($section['paragraphs'] ?? []) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach

                @if (! empty($section['bullets']))
                    <ul>
                        @foreach ($section['bullets'] as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>
                @endif
            </article>
        @endforeach
    </div>
</section>
@endsection

