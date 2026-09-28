<footer {{ $attributes->class('') }}>
    <span>
        &copy; {{ date('Y') }} SindHeuteFerien.de – Alle Angaben ohne Gewähr.
    </span>
    <span>
        Datenquelle:
        <a href="https://www.kmk.org" target="_blank" rel="noopener noreferrer">Kultusministerkonferenz (KMK)</a>
    </span>
    <nav>
        <a href="{{ route('impressum') }}">Impressum</a>
        <a href="{{ route('datenschutz') }}">Datenschutz</a>
        <a href="{{ route('github') }}">GitHub</a>
    </nav>
</footer>
