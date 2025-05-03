@php use App\Services\HolidayService;use App\Services\NaturalLanguageService; @endphp
@php
    $holidayService = app(HolidayService::class);
@endphp
@php
    $bundeslaender = [
        'bw' => ['name' => 'Baden-Württemberg', 'route' => 'baden-wuerttemberg'],
        'by' => ['name' => 'Bayern', 'route' => 'bayern'],
        'be' => ['name' => 'Berlin', 'route' => 'berlin'],
        'bb' => ['name' => 'Brandenburg', 'route' => 'brandenburg'],
        'hb' => ['name' => 'Bremen', 'route' => 'bremen'],
        'hh' => ['name' => 'Hamburg', 'route' => 'hamburg'],
        'he' => ['name' => 'Hessen', 'route' => 'hessen'],
        'mv' => ['name' => 'Mecklenburg-Vorpommern', 'route' => 'mecklenburg-vorpommern'],
        'ni' => ['name' => 'Niedersachsen', 'route' => 'niedersachsen'],
        'nw' => ['name' => 'Nordrhein-Westfalen', 'route' => 'nordrhein-westfalen'],
        'rp' => ['name' => 'Rheinland-Pfalz', 'route' => 'rheinland-pfalz'],
        'sl' => ['name' => 'Saarland', 'route' => 'saarland'],
        'sn' => ['name' => 'Sachsen', 'route' => 'sachsen'],
        'st' => ['name' => 'Sachsen-Anhalt', 'route' => 'sachsen-anhalt'],
        'sh' => ['name' => 'Schleswig-Holstein', 'route' => 'schleswig-holstein'],
        'th' => ['name' => 'Thüringen', 'route' => 'thueringen']
    ];
@endphp
<x-layout.primary>
    @php
        $routeToKuerzel = [
            'baden-wuerttemberg' => 'bw',
            'bayern' => 'by',
            'berlin' => 'be',
            'brandenburg' => 'bb',
            'bremen' => 'hb',
            'hamburg' => 'hh',
            'hessen' => 'he',
            'mecklenburg-vorpommern' => 'mv',
            'niedersachsen' => 'ni',
            'nordrhein-westfalen' => 'nw',
            'rheinland-pfalz' => 'rp',
            'saarland' => 'sl',
            'sachsen' => 'sn',
            'sachsen-anhalt' => 'st',
            'schleswig-holstein' => 'sh',
            'thueringen' => 'th'
        ];

        $kuerzelToName = [
            'bw' => 'Baden-Württemberg',
            'by' => 'Bayern',
            'be' => 'Berlin',
            'bb' => 'Brandenburg',
            'hb' => 'Bremen',
            'hh' => 'Hamburg',
            'he' => 'Hessen',
            'mv' => 'Mecklenburg-Vorpommern',
            'ni' => 'Niedersachsen',
            'nw' => 'Nordrhein-Westfalen',
            'rp' => 'Rheinland-Pfalz',
            'sl' => 'Saarland',
            'sn' => 'Sachsen',
            'st' => 'Sachsen-Anhalt',
            'sh' => 'Schleswig-Holstein',
            'th' => 'Thüringen'
        ];

        $kuerzel = $bundesland;
        $bundeslandName = $kuerzelToName[$kuerzel] ?? 'Deutschland';
        $ferienStatus = $holidayService->areTodayHolidays($kuerzel) ? 'Ja' : 'Nein';
    @endphp
    <x-slot:header>
        <h1>Sind heute Ferien in {{ $bundeslandName }}?</h1>
        <p>Aktuelle Schulferien und Ferienzeiten für {{ $bundeslandName }}</p>
    </x-slot:header>
    <main>
        <a href="{{route('home')}}" class="back-link">← Zurück zur Übersicht</a>

        <section class="info">
            <p>Heute ist der {{ $holidayService->getNow()->format('d.m.Y') }}</p>

            @if($holidayService->areTodayHolidays($kuerzel))
                @php $holidayEnd = $holidayService->holidaysEndInDays($kuerzel); @endphp
                <p class="info-result holiday-yes">Ja, heute sind Ferien in {{ $bundeslandName }}!</p>

                @if($holidayEnd)
                    <div class="state">
                        <h2>{{ $holidayEnd['holiday_name'] }}</h2>
                        <p>
                            Zeitraum: <strong>{{ $holidayEnd['start_date'] }} bis {{ $holidayEnd['end_date'] }}</strong>
                        </p>
                        <p>
                            {!! app(NaturalLanguageService::class)
                            ->multiChoice($holidayEnd['days'], [
                                0 => 'Die Ferien enden heute',
                                1 => 'Die Ferien enden morgen',
                                2 => 'Die Ferien enden in %d Tagen'
                            ]) !!}
                            ({{ $holidayEnd['start_date'] }} - {{ $holidayEnd['end_date'] }})
                        </p>
                    </div>
                @endif
            @else
                @php $nextHoliday = $holidayService->getDaysToNextHolidays($kuerzel); @endphp
                <p class="info-result holiday-no">Nein, heute sind keine Ferien in {{ $bundeslandName }}.</p>

                @if($nextHoliday)
                    <div class="holiday-details">
                        <h2>Nächste Ferien: {{ $nextHoliday['holiday_name'] }}</h2>
                        <p>Zeitraum: <strong>{{ $nextHoliday['start_date'] }}
                                bis {{ $nextHoliday['end_date'] }}</strong></p>
                        <p>
                            Die Ferien beginnen
                            in {{ $nextHoliday['days'] }} {{ $nextHoliday['days'] === 1 ? 'Tag' : 'Tagen' }}
                            und dauern {{ $nextHoliday['duration'] }} Tage.
                        </p>
                    </div>
                @else
                    <p>Wir laden gerade die nächsten Ferientermine...</p>
                @endif
            @endif
        </section>

        <section>
            <h2>Schulferien {{ date('Y') }} in {{ $bundeslandName }}</h2>
            <p>Hier findest du alle Informationen zu den aktuellen und kommenden Schulferien in {{ $bundeslandName }}.
                Unsere Daten werden regelmäßig aktualisiert, damit du immer zuverlässige Informationen erhältst.</p>

            <p>In {{ $bundeslandName }} gibt es verschiedene Ferienarten:</p>
            <ul>
                <li>Winterferien (falls vorhanden)</li>
                <li>Osterferien / Frühlingsferien</li>
                <li>Pfingstferien (in manchen Bundesländern)</li>
                <li>Sommerferien</li>
                <li>Herbstferien</li>
                <li>Weihnachtsferien</li>
            </ul>

            <p>Die genauen Termine werden von der Kultusministerkonferenz festgelegt und können sich von Jahr zu Jahr
                leicht verschieben.</p>
        </section>

        <section class="other-states">
            <h2>Ferienzeiten in anderen Bundesländern</h2>
            <p>Schulferien unterscheiden sich je nach Bundesland. Hier kannst du die aktuellen Ferienzeiten für andere
                Bundesländer prüfen:</p>

            @php
                $bundeslaender = [
                    'bw' => ['name' => 'Baden-Württemberg', 'route' => 'baden-wuerttemberg'],
                    'by' => ['name' => 'Bayern', 'route' => 'bayern'],
                    'be' => ['name' => 'Berlin', 'route' => 'berlin'],
                    'bb' => ['name' => 'Brandenburg', 'route' => 'brandenburg'],
                    'hb' => ['name' => 'Bremen', 'route' => 'bremen'],
                    'hh' => ['name' => 'Hamburg', 'route' => 'hamburg'],
                    'he' => ['name' => 'Hessen', 'route' => 'hessen'],
                    'mv' => ['name' => 'Mecklenburg-Vorpommern', 'route' => 'mecklenburg-vorpommern'],
                    'ni' => ['name' => 'Niedersachsen', 'route' => 'niedersachsen'],
                    'nw' => ['name' => 'Nordrhein-Westfalen', 'route' => 'nordrhein-westfalen'],
                    'rp' => ['name' => 'Rheinland-Pfalz', 'route' => 'rheinland-pfalz'],
                    'sl' => ['name' => 'Saarland', 'route' => 'saarland'],
                    'sn' => ['name' => 'Sachsen', 'route' => 'sachsen'],
                    'st' => ['name' => 'Sachsen-Anhalt', 'route' => 'sachsen-anhalt'],
                    'sh' => ['name' => 'Schleswig-Holstein', 'route' => 'schleswig-holstein'],
                    'th' => ['name' => 'Thüringen', 'route' => 'thueringen']
                ];
            @endphp

            <div class="state-list">
                @foreach($bundeslaender as $k => $land)
                    @if($k !== $kuerzel)
                        <div class="state state-sm">
                            <h3>
                                <a href="{{ route('bundesland', $land['route']) }}"
                                   title="Ferien in {{ $land['name'] }}">
                                    {{ $land['name'] }}
                                </a>
                            </h3>
                            <p class="holiday-status">
                                @if($holidayService->areTodayHolidays($k))
                                    <span class="holiday-yes">Heute Ferien</span>
                                @else
                                    <span class="holiday-no">Keine Ferien</span>
                                @endif
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <section>
            <h2>Häufige Fragen zu Schulferien in {{ $bundeslandName }}</h2>

            <h3>Wann beginnen die nächsten Ferien in {{ $bundeslandName }}?</h3>
            @php $nextHoliday = $holidayService->getDaysToNextHolidays($kuerzel);
            @endphp
            @if($nextHoliday)
                <p>Die nächsten Ferien in {{ $bundeslandName }} sind die {{ $nextHoliday['holiday_name'] }}.
                    Sie beginnen am {{ $nextHoliday['start_date'] }} und enden am {{ $nextHoliday['end_date'] }}.</p>
            @else
                <p>Zur Zeit sind Ferien in {{ $bundeslandName }}!</p>
            @endif

            <h3>Wie lange dauern die Sommerferien in {{ $bundeslandName }}?</h3>
            <p>Die Sommerferien dauern in der Regel 6 Wochen. Die genauen Termine können von Jahr zu Jahr variieren.</p>

            <h3>Werden Feiertage zu den Schulferien gezählt?</h3>
            <p>Nein, Feiertage sind gesetzlich festgelegte freie Tage und werden separat von den Schulferien betrachtet,
                können aber in Ferienzeiten fallen.</p>
        </section>
    </main>
</x-layout.primary>
