<?php

namespace App\Http\Controllers;

use App\Facades\Page;
use App\Services\HolidayService;

class HomeController
{
    public function __invoke(HolidayService $holidayService)
    {
        $now = $holidayService->getNow();
        $currentYear = $now->year;
        $nextYear = $currentYear + 1;
        $radar = $holidayService->getTodayHolidayRadar();

        Page::setTitle("Sind heute Ferien? Schulferien & Ferientermine {$currentYear} / {$nextYear}");
        Page::setDescription("Sind heute Ferien in Deutschland? Finde sofort heraus, in welchen Bundesländern heute schulfrei ist, wann Ferienbeginn oder Ferienende ist und alle Schulferien {$currentYear} im Überblick.");
        Page::setCanonical(route('home'));
        Page::setKeywords("Sind heute Ferien, Schulferien heute, ferienbeginn heute welche bundesländer, in welchen bundesländern enden heute die ferien, ferienende heute welches bundesland, wann sind wieder ferien, Ferienkalender {$currentYear}");

        Page::addBreadcrumb('Startseite', route('home'));

        $todayFormatted = $now->locale('de')->translatedFormat('d. F Y');

        if (!empty($radar['ending_today'])) {
            $endingList = collect($radar['ending_today'])->map(fn($e) => "{$e['state']['name']} ({$e['holiday_name']})")->implode(', ');
            $endingAnswer = "Heute (am {$todayFormatted}) enden in folgenden Bundesländern die Ferien: {$endingList}.";
        } elseif ($radar['next_ending']) {
            $endingAnswer = "Am heutigen Tag ({$todayFormatted}) enden in keinem Bundesland die Schulferien. Die nächsten Ferien enden in {$radar['next_ending']['state']['name']} ({$radar['next_ending']['holiday_name']}) am {$radar['next_ending']['end_date']}.";
        } else {
            $endingAnswer = "Am heutigen Tag ({$todayFormatted}) enden in keinem Bundesland die Schulferien.";
        }

        if (!empty($radar['starting_today'])) {
            $startingList = collect($radar['starting_today'])->map(fn($s) => "{$s['state']['name']} ({$s['holiday_name']})")->implode(', ');
            $startingAnswer = "Heute (am {$todayFormatted}) beginnen in folgenden Bundesländern die Ferien: {$startingList}.";
        } elseif ($radar['next_starting']) {
            $startingAnswer = "Am heutigen Tag ({$todayFormatted}) beginnen in keinem Bundesland die Ferien. Die nächsten Ferien starten in {$radar['next_starting']['state']['name']} ({$radar['next_starting']['holiday_name']}) am {$radar['next_starting']['start_date']} (in {$radar['next_starting']['days_until']} Tagen).";
        } else {
            $startingAnswer = "Am heutigen Tag ({$todayFormatted}) beginnen in keinem Bundesland die Ferien.";
        }

        Page::addSchema([
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name'  => 'In welchen Bundesländern enden heute die Ferien?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $endingAnswer,
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'In welchen Bundesländern beginnen heute die Ferien?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $startingAnswer,
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'Welches Bundesland hat heute Ferienende?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $endingAnswer,
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'Woher weiß ich, ob heute in meinem Bundesland Ferien sind?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'Auf SindHeuteFerien.de siehst du für jedes der 16 deutschen Bundesländer tagesaktuell, ob heute Schulferien sind oder in wie vielen Tagen die nächsten Ferien starten.',
                    ],
                ],
            ],
        ]);

        return view('welcome', [
            'radar'       => $radar,
            'currentYear' => $currentYear,
        ]);
    }
}
