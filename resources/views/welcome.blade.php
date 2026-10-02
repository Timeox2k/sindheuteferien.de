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


    <main>
        {{-- Tages-Radar für Ferienbeginn und Ferienende heute --}}
        <section class="panel" id="tages-radar" style="margin-bottom: 1.5rem;">
            <h2 class="panel-header" style="margin-bottom: 0.75rem;">
                Ferienbeginn & Ferienende heute ({{ $holidayService->getNow()->locale('de')->translatedFormat('d. F Y') }})
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 0.75rem;">
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 1rem;">
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; color: #1e3a8a;">In welchen Bundesländern enden heute die Ferien?</h3>
                    @if(!empty($radar['ending_today']))
                        <p style="margin: 0; font-weight: 600; color: #059669;">
                            Heute haben Ferienende:
                        </p>
                        <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem;">
                            @foreach($radar['ending_today'] as $item)
                                <li>
                                    <a href="{{ route('bundesland', ['bundesland' => $item['state']['slug']]) }}" class="action-link" style="margin: 0;">
                                        {{ $item['state']['name'] }} ({{ $item['holiday_name'] }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="margin: 0; color: #475569; font-size: 0.95rem;">
                            Heute enden in <strong>keinem</strong> Bundesland die Schulferien.
                        </p>
                        @if($radar['next_ending'])
                            <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: #64748b;">
                                Nächstes Ferienende: <a href="{{ route('bundesland', ['bundesland' => $radar['next_ending']['state']['slug']]) }}">{{ $radar['next_ending']['state']['name'] }}</a> am {{ $radar['next_ending']['end_date'] }} ({{ $radar['next_ending']['holiday_name'] }}).
                            </p>
                        @endif
                    @endif
                </div>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 1rem;">
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; color: #1e3a8a;">In welchen Bundesländern beginnen heute die Ferien?</h3>
                    @if(!empty($radar['starting_today']))
                        <p style="margin: 0; font-weight: 600; color: #059669;">
                            Heute ist Ferienbeginn in:
                        </p>
                        <ul style="margin: 0.5rem 0 0; padding-left: 1.25rem;">
                            @foreach($radar['starting_today'] as $item)
                                <li>
                                    <a href="{{ route('bundesland', ['bundesland' => $item['state']['slug']]) }}" class="action-link" style="margin: 0;">
                                        {{ $item['state']['name'] }} ({{ $item['holiday_name'] }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p style="margin: 0; color: #475569; font-size: 0.95rem;">
                            Heute beginnen in <strong>keinem</strong> Bundesland die Schulferien.
                        </p>
                        @if($radar['next_starting'])
                            <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: #64748b;">
                                Nächster Ferienbeginn: <a href="{{ route('bundesland', ['bundesland' => $radar['next_starting']['state']['slug']]) }}">{{ $radar['next_starting']['state']['name'] }}</a> am {{ $radar['next_starting']['start_date'] }} (in {{ $radar['next_starting']['days_until'] }} Tagen).
                            </p>
                        @endif
                    @endif
                </div>
            </div>
        </section>

        <section class="panel" id="bundeslaender" style="margin-bottom: 1.5rem;">
            <h2 class="panel-header" style="margin-bottom: 0.5rem;">Übersicht aller 16 Bundesländer</h2>
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
            <div style="font-size: 0.95rem; color: #334155; line-height: 1.6;">
                <p style="margin: 0.5rem 0;">
                    Die Ferienordnung in der Bundesrepublik Deutschland basiert auf Beschlüssen der Ständigen Konferenz der Kultusminister der Länder (Kultusministerkonferenz / KMK). Gemäß dem sogenannten Hamburger Abkommen stehen jedem Schüler pro Schuljahr insgesamt <strong>75 Werktage Ferien</strong> zu (einschließlich 12 Samstage).
                </p>

                <h3 style="font-size: 1rem; color: #0f172a; margin: 1rem 0 0.25rem;">Das rollierende System der Sommerferien</h3>
                <p style="margin: 0.25rem 0;">
                    Um ein bundesweites Verkehrschaos auf den Autobahnen zu vermeiden und die touristische Infrastruktur gleichmäßig auszulasten, beginnen die Sommerferien in Deutschland nicht gleichzeitig. Die 16 Bundesländer sind in fünf Ländergruppen aufgeteilt, deren Ferienbeginn im Sommer von Jahr zu Jahr rolliert. Ausnahmen bilden Bayern und Baden-Württemberg, die aufgrund traditioneller Pfingstferien und landwirtschaftlicher Wurzeln stets als letzte Länder in die Sommerferien starten.
                </p>

                <h3 style="font-size: 1rem; color: #0f172a; margin: 1rem 0 0.25rem;">Eigenverantwortung bei Herbst-, Winter- und Osterferien</h3>
                <p style="margin: 0.25rem 0;">
                    Während die Sommerferien zentral über mehrere Jahre im Voraus durch die KMK vereinbart werden, legen die einzelnen Bundesländer ihre Termine für die Herbst-, Weihnachts-, Winter- und Osterferien in eigener Zuständigkeit fest. Dadurch variieren beispielsweise die Winterferien oder Pfingstferien je nach Bundesland zwischen wenigen Tagen und zwei Wochen.
                </p>

                <h3 style="font-size: 1rem; color: #0f172a; margin: 1rem 0 0.25rem;">Bewegliche Ferientage</h3>
                <p style="margin: 0.25rem 0;">
                    Zusätzlich zu den festen Schulferien stehen den Schulen in vielen Bundesländern sogenannte bewegliche Ferientage zur Verfügung. Diese werden von den Schulkonferenzen oder Schulämtern vor Ort beschlossen, um lokale Brauchtumstage (wie Karneval / Fastnacht) oder Brückentage nach Feiertagen unterrichtsfrei zu gestalten.
                </p>
            </div>
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
