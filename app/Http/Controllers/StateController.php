<?php

namespace App\Http\Controllers;

use App\Facades\Page;
use App\Services\HolidayService;
use App\Services\PageService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __invoke(Request $request, HolidayService $holidayService)
    {
        $routeSlug = $request->route('bundesland') ?? $request->route('route');

        $state = $holidayService->getStateBySlug($routeSlug)
            ?? $holidayService->getStateByKuerzel($routeSlug);

        if (!$state) {
            abort(404);
        }

        if ($routeSlug !== $state['slug']) {
            return redirect()->route('bundesland', ['bundesland' => $state['slug']], 301);
        }

        $now = $holidayService->getNow();
        $currentYear = $now->year;
        $nextYear = $currentYear + 1;
        $stateName = $state['name'];
        $stateShort = $state['short'];
        $stateSlug = $state['slug'];
        $inPrefix = $state['in_prefix'];

        $isTodayHoliday = $holidayService->areTodayHolidays($state['kuerzel']);
        $nextHoliday = $holidayService->getDaysToNextHolidays($state['kuerzel']);
        $holidayEnd = $holidayService->holidaysEndInDays($state['kuerzel']);
        $groupedHolidays = $holidayService->getHolidaysGroupedByYear($state['kuerzel'], [2025, 2026, 2027]);

        $title = "Ferien {$stateName} ({$stateShort}) {$currentYear}: Sind heute Ferien? Termine & Kalender";
        
        if ($isTodayHoliday && $holidayEnd) {
            $descStatus = "Ja, heute sind {$holidayEnd['holiday_name']} {$inPrefix}{$stateName} (bis {$holidayEnd['end_date']}).";
        } elseif ($nextHoliday) {
            $descStatus = "Nein, heute sind keine Ferien {$inPrefix}{$stateName}. Nächste Ferien: {$nextHoliday['holiday_name']} ab {$nextHoliday['start_date']} (in {$nextHoliday['days']} Tagen).";
        } else {
            $descStatus = "Aktuelle Schulferien und Ferientermine {$inPrefix}{$stateName}.";
        }

        $description = "{$descStatus} Alle Schulferien {$currentYear} & {$nextYear} für {$stateName} ({$stateShort}) mit Terminen, Kalenderwochen und Countdown im Überblick.";

        Page::setTitle($title);
        Page::setDescription($description);
        Page::setCanonical(route('bundesland', ['bundesland' => $stateSlug]));
        Page::setKeywords("Ferien {$stateName}, Schulferien {$stateName} {$currentYear}, sind heute ferien {$inPrefix}{$stateName}, wann sind wieder ferien in {$stateName}, Herbstferien {$stateShort} {$currentYear}, Sommerferien {$stateName} {$currentYear}, Ferienkalender {$stateShort}");

        Page::addBreadcrumb('Startseite', route('home'));
        Page::addBreadcrumb("Ferien {$stateName}", route('bundesland', ['bundesland' => $stateSlug]));

        $faqQuestions = [];

        $faqQuestions[] = [
            '@type' => 'Question',
            'name'  => "Sind heute Ferien {$inPrefix}{$stateName}?",
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $isTodayHoliday && $holidayEnd
                    ? "Ja, heute ({$now->format('d.m.Y')}) sind in {$stateName} Schulferien ({$holidayEnd['holiday_name']}). Die Ferien dauern noch bis zum {$holidayEnd['end_date']}."
                    : "Nein, heute ({$now->format('d.m.Y')}) sind keine Schulferien {$inPrefix}{$stateName}."
            ],
        ];

        if ($nextHoliday) {
            $faqQuestions[] = [
                '@type' => 'Question',
                'name'  => "Wann sind wieder Ferien in {$stateName}?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => "Die nächsten Ferien in {$stateName} sind die {$nextHoliday['holiday_name']}. Sie beginnen am {$nextHoliday['start_date']} und dauern bis zum {$nextHoliday['end_date']} (in {$nextHoliday['days']} Tagen)."
                ],
            ];
        }

        if (isset($groupedHolidays[$currentYear])) {
            $holidayListStr = $groupedHolidays[$currentYear]->map(function ($h) {
                return "{$h['name']} ({$h['start_date']} – {$h['end_date']})";
            })->implode(', ');

            $faqQuestions[] = [
                '@type' => 'Question',
                'name'  => "Welche Schulferien gibt es {$currentYear} in {$stateName}?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => "Im Schuljahr / Kalenderjahr {$currentYear} gibt es in {$stateName} folgende Ferien: {$holidayListStr}."
                ],
            ];
        }

        Page::addSchema([
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $faqQuestions,
        ]);

        return view('sind-heute-ferien-in', [
            'state'           => $state,
            'stateName'       => $stateName,
            'stateShort'      => $stateShort,
            'stateSlug'       => $stateSlug,
            'inPrefix'        => $inPrefix,
            'bundesland'      => $state['kuerzel'],
            'isTodayHoliday'  => $isTodayHoliday,
            'holidayEnd'      => $holidayEnd,
            'nextHoliday'     => $nextHoliday,
            'groupedHolidays' => $groupedHolidays,
            'allStates'       => $holidayService->getAllStates(),
            'currentYear'     => $currentYear,
        ]);
    }
}
