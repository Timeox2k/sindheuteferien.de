@php
    use App\Facades\Page;
    use App\Services\HolidayService;
    use App\Services\NaturalLanguageService;

    $holidayService = app(HolidayService::class);
    $label = app(NaturalLanguageService::class);
    $states = $holidayService->getAllStates();
    $currentYear = $holidayService->getNow()->year;
@endphp

<x-layout.primary>
    <x-slot:header>
        <h1>Sind heute Ferien?</h1>
        <p>Tagesaktueller Status, nächste Ferientermine und Schulferien {{ $currentYear }} / {{ $currentYear + 1 }} für alle 16 deutschen Bundesländer.</p>
    </x-slot:header>

    <nav class="navbar" aria-label="Schnellnavigation Bundesländer">
        <ul>
            @foreach($states as $state)
                <li>
                    <a href="{{ route('bundesland', ['bundesland' => $state['slug']]) }}" title="Ferien in {{ $state['name'] }}">{{ $state['short'] }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    <main>
        <section class="panel" style="margin-bottom: 1.5rem;">
            <h2 class="panel-header" style="margin-bottom: 0.5rem;">Schulferien-Status am {{ $holidayService->getNow()->locale('de')->translatedFormat('l, d. F Y') }}</h2>
            <p style="margin: 0; color: #475569; font-size: 0.95rem;">
                Wähle ein Bundesland aus, um die detaillierten Termine, den aktuellen Countdown und die vollständigen Jahreskalender einzusehen:
            </p>
        </section>

        <div class="state-list">
            @foreach($states as $state)
                @php
                    $kuerzel = $state['kuerzel'];
                    $slug = $state['slug'];
                    $name = $state['name'];
                    $isHoliday = $holidayService->areTodayHolidays($kuerzel);
                    $holidayEnd = $isHoliday ? $holidayService->holidaysEndInDays($kuerzel) : null;
                    $nextHoliday = !$isHoliday ? $holidayService->getDaysToNextHolidays($kuerzel) : null;
                @endphp
                <a href="{{ route('bundesland', ['bundesland' => $slug]) }}" class="state-card" title="Ferienkalender für {{ $name }}">
                    <div>
                        <h3>{{ $name }}</h3>
                        <p class="status-indicator {{ $isHoliday ? 'yes' : 'no' }}">
                            {{ $isHoliday ? '● Heute Ferien' : '● Keine Ferien' }}
                        </p>
                    </div>
                    <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 0.5rem;">
                        @if($isHoliday && $holidayEnd)
                            {{ $holidayEnd['holiday_name'] }} (bis {{ $holidayEnd['end_date'] }})
                        @elseif($nextHoliday)
                            Nächste: {{ $nextHoliday['holiday_name'] }} (in {{ $nextHoliday['days'] }} T.)
                        @else
                            Termine einsehen →
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <section class="panel" style="margin-top: 2rem;">
            <h2 class="panel-header">Offizielle Schulferien in Deutschland – Termine & Regelungen</h2>
            <p style="font-size: 0.95rem; color: #334155; line-height: 1.6;">
                Die Termine für die Schulferien in den 16 Bundesländern werden von der Kultusministerkonferenz (KMK) koordiniert.
                Gemäß dem Hamburger Abkommen beträgt die Gesamtdauer der Ferien innerhalb eines Schuljahres 75 Werktage (einschließlich 12 Samstage).
                Während die Sommerferien zwischen den Ländern gestaffelt festgelegt werden, bestimmen die Länder die Termine für Herbst-,
                Weihnachts-, Winter-, Oster- und Pfingstferien eigenständig.
            </p>
        </section>

        <section class="panel">
            <h2 class="panel-header">Wichtige Bundesländer im Direktzugriff</h2>
            <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.95rem; line-height: 1.8;">
                <li><a href="{{ route('bundesland', ['bundesland' => 'nordrhein-westfalen']) }}" class="action-link">Schulferien in Nordrhein-Westfalen (NRW)</a></li>
                <li><a href="{{ route('bundesland', ['bundesland' => 'bayern']) }}" class="action-link">Schulferien in Bayern (BY)</a></li>
                <li><a href="{{ route('bundesland', ['bundesland' => 'baden-wuerttemberg']) }}" class="action-link">Schulferien in Baden-Württemberg (BW)</a></li>
                <li><a href="{{ route('bundesland', ['bundesland' => 'niedersachsen']) }}" class="action-link">Schulferien in Niedersachsen (NI)</a></li>
                <li><a href="{{ route('bundesland', ['bundesland' => 'hessen']) }}" class="action-link">Schulferien in Hessen (HE)</a></li>
                <li><a href="{{ route('bundesland', ['bundesland' => 'sachsen']) }}" class="action-link">Schulferien in Sachsen (SN)</a></li>
            </ul>
        </section>
    </main>
</x-layout.primary>
