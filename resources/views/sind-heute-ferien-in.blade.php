@php
    use App\Services\HolidayService;
    use App\Services\NaturalLanguageService;
    $label = app(NaturalLanguageService::class);
    $holidayService = app(HolidayService::class);
@endphp

<x-layout.primary>
    <x-slot:header>
        <h1>Sind heute Ferien {{ $inPrefix }}{{ $stateName }}?</h1>
        <p>Tagesaktueller Status, nächste Termine und Schulferien-Kalender {{ $currentYear }} / {{ $currentYear + 1 }} für {{ $stateName }} ({{ $stateShort }}).</p>
    </x-slot:header>

    <main>
        <nav class="breadcrumbs" aria-label="Pfadnavigation">
            <a href="{{ route('home') }}">Startseite</a>
            <span class="separator">›</span>
            <span class="current">Ferien {{ $stateName }}</span>
        </nav>

        <section class="status-box" id="status">
            <div class="status-bar-header">
                Ferien-Status für heute, den {{ $holidayService->getNow()->format('d.m.Y') }}
            </div>
            <div class="status-body">
                @if($isTodayHoliday && $holidayEnd)
                    <div class="status-banner is-holiday">
                        <span class="status-banner-badge">Schulfrei</span>
                        <p class="status-banner-text">
                            Ja, heute sind Schulferien {{ $inPrefix }}{{ $stateName }}!
                        </p>
                    </div>
                    <div class="next-holiday-notice">
                        <h3>Laufende Ferien: {{ $holidayEnd['holiday_name'] }}</h3>
                        <div class="next-holiday-details">
                            <div class="item">Zeitraum: <strong>{{ $holidayEnd['start_date'] }} bis {{ $holidayEnd['end_date'] }}</strong></div>
                            <div class="item">
                                @if($holidayEnd['days'] === 0)
                                    Status: <strong>Enden heute</strong>
                                @elseif($holidayEnd['days'] === 1)
                                    Status: <strong>Enden morgen</strong>
                                @else
                                    Status: <strong>Noch {{ $holidayEnd['days'] }} Tage bis zum Schulstart</strong>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $holidayEnd['slug']]) }}" class="action-link">
                            Detailseite zu den {{ $holidayEnd['holiday_name'] }} öffnen →
                        </a>
                    </div>
                @else
                    <div class="status-banner is-not-holiday">
                        <span class="status-banner-badge">Regulärer Unterricht</span>
                        <p class="status-banner-text">
                            Nein, heute sind keine Ferien {{ $inPrefix }}{{ $stateName }}.
                        </p>
                    </div>

                    @if($nextHoliday)
                        <div class="next-holiday-notice" id="naechste-ferien">
                            <h3>Nächste Schulferien in {{ $stateName }}</h3>
                            <div class="next-holiday-details">
                                <div class="item">Ferien: <strong>{{ $nextHoliday['holiday_name'] }}</strong></div>
                                <div class="item">Zeitraum: <strong>{{ $nextHoliday['start_date'] }} – {{ $nextHoliday['end_date'] }}</strong></div>
                                <div class="item">Dauer: <strong>{{ $nextHoliday['duration'] }} freie Tage</strong></div>
                                <div class="item">
                                    <span class="countdown-tag">In {{ $nextHoliday['days'] }} {{ $nextHoliday['days'] === 1 ? 'Tag' : 'Tagen' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $nextHoliday['slug']]) }}" class="action-link">
                                Details zu den {{ $nextHoliday['holiday_name'] }} {{ $stateShort }} ansehen →
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        @foreach([$currentYear, $currentYear + 1, $currentYear - 1] as $year)
            @if(isset($groupedHolidays[$year]) && $groupedHolidays[$year]->isNotEmpty())
                <section class="panel" id="ferien-{{ $year }}">
                    <h2 class="panel-header">Schulferien {{ $year }} in {{ $stateName }} ({{ $stateShort }})</h2>
                    <p style="font-size: 0.95rem; color: #475569; margin-top: 0;">
                        Offizielle Ferientermine der Kultusministerkonferenz für {{ $stateName }} im Jahr {{ $year }}:
                    </p>

                    <div class="table-container">
                        <table class="holiday-table">
                            <thead>
                                <tr>
                                    <th>Ferien</th>
                                    <th>Zeitraum</th>
                                    <th>Dauer</th>
                                    <th>Kalenderwoche</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedHolidays[$year] as $holiday)
                                    <tr>
                                        <td>
                                            <a href="{{ route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $holiday['slug']]) }}">
                                                {{ $holiday['name'] }} {{ $year }}
                                            </a>
                                        </td>
                                        <td>{{ $holiday['start_date'] }} – {{ $holiday['end_date'] }}</td>
                                        <td>{{ $holiday['duration'] }} Tage</td>
                                        <td>
                                            @if($holiday['start_kw'] === $holiday['end_kw'])
                                                KW {{ $holiday['start_kw'] }}
                                            @else
                                                KW {{ $holiday['start_kw'] }}–{{ $holiday['end_kw'] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($holiday['status'] === 'active')
                                                <span class="status-pill active">Läuft gerade</span>
                                            @elseif($holiday['status'] === 'upcoming')
                                                <span class="status-pill upcoming">In {{ $holiday['days_diff'] }} Tagen</span>
                                            @else
                                                <span class="status-pill past">Vorbei</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        @endforeach

        <section class="panel" id="faq">
            <h2 class="panel-header">Häufig gestellte Fragen zu den Schulferien in {{ $stateName }}</h2>

            <div class="faq-list">
                <div class="faq-card">
                    <details open>
                        <summary>Sind heute Ferien {{ $inPrefix }}{{ $stateName }}?</summary>
                        <div class="faq-content">
                            @if($isTodayHoliday && $holidayEnd)
                                <p><strong>Ja</strong>, heute am {{ $holidayService->getNow()->format('d.m.Y') }} sind in {{ $stateName }} Schulferien ({{ $holidayEnd['holiday_name'] }}). Die Ferien dauern noch bis zum {{ $holidayEnd['end_date'] }}.</p>
                            @else
                                <p><strong>Nein</strong>, heute am {{ $holidayService->getNow()->format('d.m.Y') }} finden regulär Schultage in {{ $stateName }} statt. Es sind keine Ferien.</p>
                            @endif
                        </div>
                    </details>
                </div>

                @if($nextHoliday)
                    <div class="faq-card">
                        <details open>
                            <summary>Wann beginnen die nächsten Ferien in {{ $stateName }}?</summary>
                            <div class="faq-content">
                                <p>
                                    Die nächsten Schulferien in {{ $stateName }} sind die <strong>{{ $nextHoliday['holiday_name'] }}</strong>.
                                    Sie beginnen am <strong>{{ $nextHoliday['start_date'] }}</strong> und enden am <strong>{{ $nextHoliday['end_date'] }}</strong>.
                                    Es verbleiben noch <strong>{{ $nextHoliday['days'] }} Tage</strong> bis zum Ferienstart.
                                </p>
                            </div>
                        </details>
                    </div>
                @endif

                <div class="faq-card">
                    <details>
                        <summary>Wer legt die Ferientermine für {{ $stateName }} fest?</summary>
                        <div class="faq-content">
                            <p>
                                Die Schulferientermine werden durch die Kultusministerkonferenz (KMK) der Länder abgestimmt.
                                Während Sommerferien zwischen den Ländern rollierend koordiniert werden, legen die Landesministerien
                                Termine für Herbst-, Weihnachts- und Osterferien weitgehend eigenständig fest.
                            </p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <section class="panel" id="bundeslaender">
            <h2 class="panel-header">Ferien-Status in den weiteren Bundesländern</h2>
            <p style="font-size: 0.95rem; color: #475569; margin-top: 0;">
                Übersicht der Ferienregelungen in allen weiteren 15 Bundesländern:
            </p>

            <div class="state-list">
                @foreach($allStates as $land)
                    @if($land['slug'] !== $stateSlug)
                        <a href="{{ route('bundesland', ['bundesland' => $land['slug']]) }}"
                           class="state-card"
                           title="Ferien in {{ $land['name'] }} ({{ $land['short'] }})">
                            <h3>{{ $land['name'] }}</h3>
                            <p class="status-indicator {{ $holidayService->areTodayHolidays($land['kuerzel']) ? 'yes' : 'no' }}">
                                {{ $holidayService->areTodayHolidays($land['kuerzel']) ? '● Heute Ferien' : '● Keine Ferien' }}
                            </p>
                        </a>
                    @endif
                @endforeach
            </div>
        </section>
    </main>
</x-layout.primary>
