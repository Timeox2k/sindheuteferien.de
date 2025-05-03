<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SchoolHolidayController extends Controller
{
    public static function getNow(): Carbon
    {
        $request = app(Request::class);

        // You can use ?now=2025-05-01 to test other dates, for example
        if ($request->has("now")) {
            return $request->date('now');
        }

        return now();
    }

    /**
     * @param string $bundeslandSlug
     * @return string The ISO 3166-2 code for the Bundesland, fallback to $bundeslandSlug
     */
    private static function getBundeslandCode(string $bundeslandSlug): string
    {
        $bundeslandMap = [
            'bw' => 'DE-BW',
            'by' => 'DE-BY',
            'be' => 'DE-BE',
            'bb' => 'DE-BB',
            'hb' => 'DE-HB',
            'hh' => 'DE-HH',
            'he' => 'DE-HE',
            'mv' => 'DE-MV',
            'ni' => 'DE-NI',
            'nw' => 'DE-NW',
            'rp' => 'DE-RP',
            'sl' => 'DE-SL',
            'sn' => 'DE-SN',
            'st' => 'DE-ST',
            'sh' => 'DE-SH',
            'th' => 'DE-TH'
        ];

        return Arr::get($bundeslandMap, $bundeslandSlug, $bundeslandSlug);
    }

    public static function areTodayHolidays($bundesland)
    {
        $today = self::getNow()->format('Y-m-d');

        // Konvertierung der Kürzel
        $bundeslandCode = self::getBundeslandCode($bundesland);
        // Alle aktuellen Ferien abrufen und in PHP filtern
        $currentHolidays = \App\Models\SchoolHoliday::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        // Manuell filtern
        foreach ($currentHolidays as $holiday) {
            if ($holiday->nationwide) {
                return true;
            }

            if (self::matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                return true;
            }
        }

        return false;
    }

    public static function getDaysToNextHolidays($bundesland, $minDurationDays = 2)
    {
        $today = self::getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');

        // Konvertierung der Kürzel
        $bundeslandCode = self::getBundeslandCode($bundesland);

        // Prüfen, ob heute bereits Ferien sind
        if (self::areTodayHolidays($bundesland)) {
            return 0;
        }

        // Nächste Ferien finden, die nach heute beginnen
        $nextHolidays = \App\Models\SchoolHoliday::whereDate('start_date', '>', $todayFormatted)
            ->whereRaw('julianday(end_date) - julianday(start_date) >= ?', [$minDurationDays - 1])
            ->orderBy('start_date', 'asc')
            ->get();

        if ($nextHolidays->isEmpty()) {
            return null;
        }

        // Finde die frühesten Ferien, für die das Bundesland gilt
        $nextHoliday = null;
        foreach ($nextHolidays as $holiday) {
            if ($holiday->nationwide) {
                $nextHoliday = $holiday;
                break;
            }

            if (self::matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                $nextHoliday = $holiday;
                break;
            }
        }

        if (!$nextHoliday) {
            return null;
        }

        // Berechne die Anzahl der Tage zwischen heute und dem Beginn der nächsten Ferien
        $nextHolidayStartDate = \Carbon\Carbon::parse($nextHoliday->start_date)->startOfDay();
        $nextHolidayEndDate = \Carbon\Carbon::parse($nextHoliday->end_date)->startOfDay();
        $daysUntilNextHoliday = $today->diffInDays($nextHolidayStartDate);
        $holidayDuration = $nextHolidayStartDate->diffInDays($nextHolidayEndDate) + 1;

        return [
            'days'         => (int)$daysUntilNextHoliday,
            'holiday_name' => $nextHoliday->name,
            'start_date'   => $nextHolidayStartDate->format('d.m.Y'),
            'end_date'     => $nextHolidayEndDate->format('d.m.Y'),
            'duration'     => $holidayDuration
        ];
    }

    public static function holidaysEndInDays($bundesland)
    {
        // Get the current date
        $today = self::getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');
        $bundeslandCode = self::getBundeslandCode($bundesland);

        // Prüfen, ob heute überhaupt Ferien sind
        if (!self::areTodayHolidays($bundesland)) {
            return null;
        }

        // Finde aktuelle Ferien
        $currentHolidays = \App\Models\SchoolHoliday::whereDate('start_date', '<=', $todayFormatted)
            ->whereDate('end_date', '>=', $todayFormatted)
            ->orderBy('end_date', 'asc')
            ->get();

        if ($currentHolidays->isEmpty()) {
            return null;
        }

        // Finde die aktuellen Ferien für das Bundesland
        $currentHoliday = null;
        foreach ($currentHolidays as $holiday) {
            if ($holiday->nationwide) {
                $currentHoliday = $holiday;
                break;
            }

            if (self::matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                $currentHoliday = $holiday;
                break;
            }
        }

        if (!$currentHoliday) {
            return null;
        }

        // Berechne die Anzahl der Tage bis zum Ende der Ferien
        $holidayEndDate = \Carbon\Carbon::parse($currentHoliday->end_date)->startOfDay();
        $daysUntilHolidayEnd = $today->diffInDays($holidayEndDate);

        return [
            'days'         => (int)$daysUntilHolidayEnd,
            'holiday_name' => $currentHoliday->name,
            'start_date'   => \Carbon\Carbon::parse($currentHoliday->start_date)->format('d.m.Y'),
            'end_date'     => $holidayEndDate->format('d.m.Y')
        ];
    }

    private static function matchesBundesland($subdivisions, $bundeslandCode)
    {
        // Überprüfe, ob $subdivisions ein String ist und dekodiere ihn in diesem Fall
        if (is_string($subdivisions)) {
            $subdivisions = json_decode($subdivisions, true);
        }

        // Strukturprüfung und flexible Suche
        if (is_array($subdivisions)) {
            // Durchsuche das Array nach dem Bundesland-Code
            $jsonString = json_encode($subdivisions);

            return strpos($jsonString, '"code":"' . $bundeslandCode . '"') !== false ||
                strpos($jsonString, '"code":"' . $bundeslandCode . '-') !== false;
        }

        return false;
    }
}
