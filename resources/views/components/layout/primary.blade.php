@php
    use App\Facades\Page;
@endphp<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="robots" content="{{ Page::getRobots() }}"/>
    @if(filled(Page::getDescription()))
        <meta name="description" content="{!! Page::getDescription() !!}"/>
    @endif
    @if(filled(Page::getKeywords()))
        <meta name="keywords" content="{{ Page::getKeywords() }}"/>
    @endif
    <meta name="author" content="{{ Page::getAuthor() }}"/>
    <link rel="canonical" href="{{ Page::getCanonical() ?? url()->current() }}"/>

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="{{ Page::getOgType() }}"/>
    <meta property="og:url" content="{{ Page::getCanonical() ?? url()->current() }}"/>
    <meta property="og:title" content="{{ Page::hasTitle() ? Page::getTitle() : Page::getAppName() }}"/>
    @if(filled(Page::getDescription()))
        <meta property="og:description" content="{!! Page::getDescription() !!}"/>
    @endif
    <meta property="og:site_name" content="SindHeuteFerien.de"/>
    <meta property="og:locale" content="de_DE"/>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content="{{ Page::hasTitle() ? Page::getTitle() : Page::getAppName() }}"/>
    @if(filled(Page::getDescription()))
        <meta name="twitter:description" content="{!! Page::getDescription() !!}"/>
    @endif

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg"/>
    <link rel="icon" type="image/png" sizes="48x48" href="/favicon-48x48.png"/>
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"/>

    <title>
        @if(Page::hasTitle())
            {!! Page::getTitle() !!}
        @else
            {{ Page::getAppName() }} – Schulferien & Ferientermine aktuell
        @endif
    </title>

    <!-- Schema.org JSON-LD -->
    @foreach(Page::getSchemas() as $schema)
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endforeach

    <x-parts.style/>
</head>
<body>
@if(isset($header))
    <header>
        @if(!Request::is("/"))
            <div class="top-bar">
                <a href="{{ route('home') }}" class="hidden-md-block">
                    ← Übersicht aller Bundesländer
                </a>
                <a href="{{ route('home') }}" class="block-md-none">
                    ← Übersicht
                </a>
            </div>
        @endif
        <div class="header-container">
            {!! $header !!}
        </div>
    </header>
@endif
{!! $slot !!}
<x-parts.footer/>
</body>
</html>
