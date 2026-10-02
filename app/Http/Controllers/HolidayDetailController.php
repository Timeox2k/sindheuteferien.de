<?php

namespace App\Http\Controllers;

use App\Facades\Page;
use App\Services\HolidayService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayDetailController extends Controller
{
    public function __invoke(Request $request, HolidayService $holidayService)
    {
        $bundeslandSlug = $request->route('bundesland');
        $ferienSlug = $request->route('ferien');

        $detail = $holidayService->getHolidayDetail($bundeslandSlug, $ferienSlug);

        if (!$detail) {
            abort(404);
        }

        $state = $detail['state'];
        $holiday = $detail['holiday'];
        $stateName = $state['name'];
        $stateShort = $state['short'];
        $stateSlug = $state['slug'];
        $inPrefix = $state['in_prefix'];
        $holidayName = $holiday['name'];
        $holidayYear = $holiday['year'];

        $today = $holidayService->getNow()->startOfDay();
        $startDate = $detail['start_carbon'];
        $endDate = $detail['end_carbon'];
        $daysUntilStart = $detail['days_until_start'];
        $daysUntilEnd = $detail['days_until_end'];

        $title = "{$holidayName} {$stateShort} {$holidayYear} ({$stateName}) – Termine & Countdown";

        $dateRangeStr = "{$holiday['start_date']} bis {$holiday['end_date']}";
        $kwStr = $holiday['start_kw'] === $holiday['end_kw']
            ? "KW {$holiday['start_kw']}"
            : "KW {$holiday['start_kw']}–{$holiday['end_kw']}";

        $description = "Wann beginnen die {$holidayName} {$holidayYear} {$inPrefix}{$stateName} ({$stateShort})? Alle genauen Ferientermine, Reisedauer, Kalenderwochen und Live-Countdown jetzt ansehen.";

        Page::setTitle($title);
        Page::setDescription($description);
        Page::setCanonical(route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $holiday['slug']]));
        Page::setKeywords("{$holidayName} {$stateShort} {$holidayYear}, {$holidayName} {$stateName} {$holidayYear}, wann sind {$holidayName} {$holidayYear} in {$stateName}, ferien {$stateShort} termine, ferienkalender {$stateName}");

        Page::addBreadcrumb('Startseite', route('home'));
        Page::addBreadcrumb("Ferien {$stateName}", route('bundesland', ['bundesland' => $stateSlug]));
        Page::addBreadcrumb("{$holidayName} {$holidayYear}", route('holiday.detail', ['bundesland' => $stateSlug, 'ferien' => $holiday['slug']]));

        $faqQuestions = [
            [
                '@type' => 'Question',
                'name'  => "Wann beginnen die {$holidayName} {$holidayYear} in {$stateName}?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => "Die {$holidayName} {$holidayYear} {$inPrefix}{$stateName} beginnen am {$holiday['start_date']} und enden am {$holiday['end_date']}."
                ],
            ],
            [
                '@type' => 'Question',
                'name'  => "Wie viele freie Tage haben Schüler bei den {$holidayName} {$holidayYear} in {$stateName}?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => "Die {$holidayName} {$holidayYear} in {$stateName} dauern insgesamt {$holiday['duration']} Tage (einschließlich Wochenenden)."
                ],
            ],
            [
                '@type' => 'Question',
                'name'  => "In welcher Kalenderwoche liegen die {$holidayName} {$holidayYear} in {$stateName}?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => "Die {$holidayName} {$holidayYear} {$inPrefix}{$stateName} liegen in {$kwStr}."
                ],
            ],
        ];

        Page::addSchema([
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $faqQuestions,
        ]);

        return view('holiday-detail', [
            'state'           => $state,
            'stateName'       => $stateName,
            'stateShort'      => $stateShort,
            'stateSlug'       => $stateSlug,
            'inPrefix'        => $inPrefix,
            'holiday'         => $holiday,
            'holidayName'     => $holidayName,
            'holidayYear'     => $holidayYear,
            'dateRangeStr'    => $dateRangeStr,
            'kwStr'           => $kwStr,
            'today'           => $today,
            'daysUntilStart'  => $daysUntilStart,
            'daysUntilEnd'    => $daysUntilEnd,
            'otherYears'      => $detail['other_years'],
            'otherStates'     => $detail['other_states'],
        ]);
    }
}
