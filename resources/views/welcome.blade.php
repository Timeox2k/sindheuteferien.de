@php
    use App\Facades\Page;use App\Services\HolidayService;use App\Services\NaturalLanguageService;

    $holidayService = app(HolidayService::class);
    $label = app(NaturalLanguageService::class);
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
    <x-slot:header>
        <h1>Sind heute Ferien?</h1>
        <p>Hier findest du auf einen Blick, in welchen Bundesländern heute Schulferien sind.</p>
    </x-slot:header>
    <nav class="navbar">
        <ul>
            @foreach($bundeslaender as $kuerzel => $land)
                <li>
                    <a href="#in-{{$kuerzel}}">{{ strtoupper($kuerzel) }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
    <main>
        <section class="info">
            <h2>
                Übersicht aller Bundesländer
            </h2>
            @if($holidayService->getNow()->isToday())
                <p>Heute ist der {{ $holidayService->getNow()->format("d.m.Y") }}.</p>
            @else
                <p>Es werden Daten für den {{ $holidayService->getNow()->format("d.m.Y") }} angezeigt.</p>
            @endif
            <p>Hier siehst du, in welchen Bundesländern heute Schulferien sind:</p>
        </section>

        <div class="state-list">
            @foreach($bundeslaender as $kuerzel => $land)
                <div class="state" id="in-{{ $kuerzel }}">
                    <h3>
                        <a href="{{ route('bundesland', $land['route']) }}">
                            {{ $land['name'] }}
                        </a>
                    </h3>
                    @if($holidayService->areTodayHolidays($kuerzel))
                        @php $holidayEnd = $holidayService->holidaysEndInDays($kuerzel); @endphp
                        <div class="holiday-yes">
                            <p>
                                <u>Ja</u>, hier sind heute sind Ferien!
                            </p>
                            <p>
                                {!! $label->getEndingInString($holidayEnd) !!}
                            </p>
                            <p class="muted">
                                {{sprintf("(%s - %s)", $holidayEnd['start_date'], $holidayEnd['end_date'])}}
                            </p>
                        </div>
                    @else
                        @php $nextHoliday = $holidayService->getDaysToNextHolidays($kuerzel); @endphp
                        <div class="holiday-no">
                            <p>
                                <u>Nein</u>, heute sind keine Ferien.
                            </p>
                            @if($nextHoliday)
                                <p>
                                    {!! $label->getButNextAreStartingInString($nextHoliday) !!}
                                </p>
                                <p class="muted">
                                    {{sprintf("(%s - %s)", $nextHoliday['start_date'], $nextHoliday['end_date'])}}
                                </p>
                            @else
                                <br>
                                Wir laden gerade die nächsten Ferientermine...
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <section title="Schulferien heute – hat mein Bundesland heute frei?">
            <h2>Schulferien heute – hat mein Bundesland heute frei?</h2>
            <p>Auf sindheuteferien.de findest du schnell und unkompliziert heraus, ob heute in deinem Bundesland
                Schulferien
                sind. Perfekt für Eltern, Schüler/innen und Lehrer/innen, die wissen möchten, ob heute schulfrei ist
                oder
                wie weit die nächsten Ferien noch entfernt sind.</p>
        </section>

        <section title="Wie erkennt sindheuteferien.de, ob heute Ferien sind?">
            <h2>Wie erkennt sindheuteferien.de, ob heute Ferien sind?</h2>
            <p>Wir erhalten die Schulferienpläne aller Bundesländer aus einer zuverlässigen Quelle und stellen sie dir
                hier
                übersichtlich dar. So kannst du auf einen Blick sehen, ob heute Ferien sind oder nicht.</p>
        </section>
    </main>
</x-layout.primary>
