<?php
/**
 * File: HomeController.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Http\Controllers;

use App\Facades\Page;

class HomeController
{
    public function __invoke()
    {
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;

        Page::setTitle("Sind heute Ferien? Schulferien & Ferientermine {$currentYear} / {$nextYear}");
        Page::setDescription("Sind heute Ferien in Deutschland? Finde sofort heraus, in welchen Bundesländern heute schulfrei ist, wann die nächsten Ferien beginnen und alle Schulferien {$currentYear} im Überblick.");
        Page::setCanonical(route('home'));
        Page::setKeywords("Sind heute Ferien, Schulferien heute, Ferienkalender {$currentYear}, Schulferien Deutschland, wann sind wieder ferien, Ferienkalender {$nextYear}");

        Page::addBreadcrumb('Startseite', route('home'));

        Page::addSchema([
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name'  => 'Woher weiß ich, ob heute in meinem Bundesland Ferien sind?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'Auf SindHeuteFerien.de siehst du für jedes der 16 deutschen Bundesländer tagesaktuell, ob heute Schulferien sind oder in wie vielen Tagen die nächsten Ferien starten.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name'  => 'Wie viele Tage Schulferien gibt es in Deutschland pro Schuljahr?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => 'In Deutschland haben Schülerinnen und Schüler gemäß dem Abkommen der Kultusministerkonferenz (KMK) insgesamt 75 Werktage (inklusive Samstage) bzw. rund 63–65 Schultage Ferien im Jahr.',
                    ],
                ],
            ],
        ]);

        return view('welcome');
    }
}
