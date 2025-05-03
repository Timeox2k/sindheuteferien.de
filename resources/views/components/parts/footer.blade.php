<footer {{ $attributes->class('') }}>
    <span>
        &copy; 2025 SindHeuteFerien.de – Alle Angaben ohne Gewähr.
    </span>
    <span>
        Datenquelle:
        <a href="https://www.schulferien.org" target="_blank" rel="noopener noreferrer">schulferien.org</a>
    </span>
    <nav>
        <a href="{{ route('impressum') }}">Impressum</a>
        <a href="{{ route('datenschutz') }}">Datenschutz</a>
        <a href="{{ route('github') }}">GitHub</a>
    </nav>
</footer>
