@php
    use App\Services\HolidayService;
    $holidayService = app(HolidayService::class);
@endphp

<x-layout.primary>
    <x-slot:header>
        <h1>{{ $holidayName }} {{ $holidayYear }} in {{ $stateName }} ({{ $stateShort }})</h1>
        <p>Offizielle Ferientermine, Kalenderwochen, Dauer und Mehrjahresvergleich für {{ $stateName }}.</p>
    </x-slot:header>

    <main>
        <nav class="breadcrumbs" aria-label="Pfadnavigation">
            <a href="{{ route('home') }}">Startseite</a>
            <span class="separator">›</span>
            <a href="{{ route('bundesland', ['bundesland' => $stateSlug]) }}">Ferien {{ $stateName }}</a>
            <span class="separator">›</span>
            <span class="current">{{ $holidayName }} {{ $holidayYear }}</span>
        </nav>

        <section class="panel" id="ueberblick">
            <h2 class="panel-header">Terminübersicht: {{ $holidayName }} {{ $holidayYear }} {{ $inPrefix }}{{ $stateName }}</h2>

            <table class="facts-table">
                <tbody>
                    <tr>
                        <th>Ferienzeitraum</th>
                        <td>{{ $holiday['start_date'] }} bis {{ $holiday['end_date'] }}</td>
                    </tr>
                    <tr>
                        <th>Dauer</th>
                        <td>{{ $holiday['duration'] }} Kalendertage (inkl. Wochenenden)</td>
                    </tr>
                    <tr>
                        <th>Kalenderwoche</th>
                        <td>{{ $kwStr }}</td>
                    </tr>
                    <tr>
                        <th>Bundesland</th>
                        <td>{{ $stateName }} (Kürzel: {{ $stateShort }})</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($daysUntilStart > 0)
                                <span class="status-pill upcoming">Beginnt in {{ $daysUntilStart }} {{ $daysUntilStart === 1 ? 'Tag' : 'Tagen' }}</span>
                            @elseif($today->between($holiday['start_date_iso'], $holiday['end_date_iso']))
                                <span class="status-pill active">Läuft aktuell (noch {{ $daysUntilEnd }} {{ $daysUntilEnd === 1 ? 'Tag' : 'Tage' }})</span>
                            @else
                                <span class="status-pill past">Bereits vergangen</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="next-holiday-notice">
                <h3>Terminhinweis & Schulbeginn</h3>
                <p style="margin: 0.25rem 0 0; font-size: 0.95rem; color: #334155;">
                    Erster offizieller Ferientag ist der <strong>{{ $holiday['start_date'] }}</strong>.
                    Letzter Schultag vor den Ferien ist der Werktag davor. Der Unterricht wird planmäßig nach dem <strong>{{ $holiday['end_date'] }}</strong> wieder aufgenommen.
                </p>
            </div>
        </section>

        @if($nextHoliday)
            <div class="panel" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: #ffffff; border: none; margin-bottom: 1.5rem; padding: 1.25rem 1.5rem; border-radius: 6px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span style="display: inline-block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(255,255,255,0.15); color: #93c5fd; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700;">
                            Nächste Schulferien {{ $inPrefix }}{{ $stateName }}
                        </span>
                        <h3 style="margin: 0.4rem 0 0.2rem; font-size: 1.25rem; color: #ffffff;">
                            {{ $nextHoliday['name'] }} {{ $nextHoliday['year'] }}
                        </h3>
                        <p style="margin: 0; font-size: 0.9rem; color: #cbd5e1;">
                            Zeitraum: {{ $nextHoliday['start_date'] }} bis {{ $nextHoliday['end_date'] }} ({{ $nextHoliday['duration'] }} Tage)
                        </p>
                    </div>
                    <a href="{{ route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $nextHoliday['slug']]) }}" 
                       style="background: #ffffff; color: #1e3a8a; font-weight: 700; padding: 0.65rem 1.25rem; border-radius: 6px; text-decoration: none; font-size: 0.95rem; white-space: nowrap; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                        Termine ansehen →
                    </a>
                </div>
            </div>
        @endif

        @if($otherYears->isNotEmpty())
            <section class="panel" id="jahresvergleich">
                <h2 class="panel-header">{{ $holidayName }} in {{ $stateName }} im Mehrjahresvergleich</h2>
                <p style="font-size: 0.95rem; color: #475569; margin-top: 0;">
                    Termine der {{ $holidayName }} in {{ $stateName }} für die Schuljahre 2025 bis 2027:
                </p>

                <div class="table-container">
                    <table class="holiday-table">
                        <thead>
                            <tr>
                                <th>Jahr</th>
                                <th>Zeitraum</th>
                                <th>Dauer</th>
                                <th>Kalenderwoche</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($otherYears as $oy)
                                <tr @if($oy['year'] == $holidayYear) style="background-color: #eff6ff; font-weight: 600;" @endif>
                                    <td data-label="Jahr">
                                        {{ $oy['name'] }} {{ $oy['year'] }}
                                        @if($oy['year'] == $holidayYear)
                                            <span class="status-pill active" style="margin-left: 0.5rem;">Aktuell</span>
                                        @endif
                                    </td>
                                    <td data-label="Zeitraum">{{ $oy['start_date'] }} – {{ $oy['end_date'] }}</td>
                                    <td data-label="Dauer">{{ $oy['duration'] }} Tage</td>
                                    <td data-label="Kalenderwoche">
                                        @if($oy['start_kw'] === $oy['end_kw'])
                                            KW {{ $oy['start_kw'] }}
                                        @else
                                            KW {{ $oy['start_kw'] }}–{{ $oy['end_kw'] }}
                                        @endif
                                    </td>
                                    <td data-label="Details">
                                        @if($oy['year'] != $holidayYear)
                                            <a href="{{ route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $oy['slug']]) }}">
                                                Termine {{ $oy['year'] }} →
                                            </a>
                                        @else
                                            –
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        @if(!empty($otherStates))
            <section class="panel" id="bundeslaender-vergleich">
                <h2 class="panel-header">Vergleich: {{ $holidayName }} {{ $holidayYear }} in anderen Bundesländern</h2>
                <p style="font-size: 0.95rem; color: #475569; margin-top: 0;">
                    Übersicht der Termine für die {{ $holidayName }} {{ $holidayYear }} im bundesweiten Vergleich:
                </p>

                <div class="table-container">
                    <table class="holiday-table">
                        <thead>
                            <tr>
                                <th>Bundesland</th>
                                <th>Zeitraum</th>
                                <th>Dauer</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color: #eff6ff; font-weight: 600;">
                                <td data-label="Bundesland">{{ $stateName }} ({{ $stateShort }})</td>
                                <td data-label="Zeitraum">{{ $holiday['start_date'] }} – {{ $holiday['end_date'] }}</td>
                                <td data-label="Dauer">{{ $holiday['duration'] }} Tage</td>
                                <td data-label="Link"><span class="status-pill active">Diese Ansicht</span></td>
                            </tr>
                            @foreach($otherStates as $os)
                                <tr>
                                    <td data-label="Bundesland">
                                        <a href="{{ route('bundesland', ['bundesland' => $os['state_slug']]) }}">
                                             {{ $os['state_name'] }}
                                        </a>
                                    </td>
                                    <td data-label="Zeitraum">{{ $os['start_date'] }} – {{ $os['end_date'] }}</td>
                                    <td data-label="Dauer">{{ $os['duration'] }} Tage</td>
                                    <td data-label="Link">
                                        <a href="{{ route('holiday.detail', ['bundesland' => $os['state_slug'], 'ferien' => $os['slug']]) }}">
                                            Termine {{ $os['state_short'] }} →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif

        <section class="panel" id="faq">
            <h2 class="panel-header">Häufige Fragen zu den {{ $holidayName }} {{ $holidayYear }} in {{ $stateName }}</h2>

            <div class="faq-list">
                <div class="faq-card">
                    <details open>
                        <summary>Wann beginnen die {{ $holidayName }} {{ $holidayYear }} in {{ $stateName }}?</summary>
                        <div class="faq-content">
                            <p>
                                Die {{ $holidayName }} {{ $holidayYear }} beginnen am <strong>{{ $holiday['start_date'] }}</strong>
                                und enden am <strong>{{ $holiday['end_date'] }}</strong>.
                            </p>
                        </div>
                    </details>
                </div>

                <div class="faq-card">
                    <details>
                        <summary>Wie viele freie Tage umfassen die {{ $holidayName }} {{ $holidayYear }}?</summary>
                        <div class="faq-content">
                            <p>
                                Die Ferien umfassen insgesamt <strong>{{ $holiday['duration'] }} Kalendertage</strong> inklusive der Wochenenden.
                            </p>
                        </div>
                    </details>
                </div>

                <div class="faq-card">
                    <details>
                        <summary>In welche Kalenderwochen fallen die {{ $holidayName }} {{ $holidayYear }} in {{ $stateName }}?</summary>
                        <div class="faq-content">
                            <p>
                                Die Ferien liegen im Bereich <strong>{{ $kwStr }}</strong>.
                            </p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <p style="margin: 2rem 0 1rem;">
            <a href="{{ route('bundesland', ['bundesland' => $stateSlug]) }}" class="action-link">
                ← Zurück zur Gesamtübersicht der Ferien in {{ $stateName }}
            </a>
        </p>
    </main>
</x-layout.primary>
