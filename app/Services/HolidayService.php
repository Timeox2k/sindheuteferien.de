<?php
/**
 * File: HolidayService.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HolidayService
{
    public function getNow(): Carbon
    {
        $request = app(Request::class);

        // You can use ?now=2025-05-01 to test other dates, for example
        if ($request->has("now")) {
            try {
                return $request->date('now');
            }
            catch (\Exception $e) {
                // Pff, then we just use the current date
            }
        }

        return now();
    }

    /**
     * @param string $bundeslandSlug
     * @return string The ISO 3166-2 code for the Bundesland, fallback to $bundeslandSlug
     */
    private function getBundeslandCode(string $bundeslandSlug): string
    {
        $bundeslandMap = $this->getBundslandSlugMap();
        return Arr::get($bundeslandMap, $bundeslandSlug, $bundeslandSlug);
    }

    public function areTodayHolidays($bundesland)
    {
        $today = $this->getNow()->format('Y-m-d');

        // Konvertierung der Kürzel
        $bundeslandCode = $this->getBundeslandCode($bundesland);
        // Alle aktuellen Ferien abrufen und in PHP filtern
        $currentHolidays = \App\Models\SchoolHoliday::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        // Manuell filtern
        foreach ($currentHolidays as $holiday) {
            if ($holiday->nationwide) {
                return true;
            }

            if ($this->matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                return true;
            }
        }

        return false;
    }

    public function getDaysToNextHolidays($bundesland, $minDurationDays = 2)
    {
        $today = $this->getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');

        // Konvertierung der Kürzel
        $bundeslandCode = $this->getBundeslandCode($bundesland);

        // Prüfen, ob heute bereits Ferien sind
        if ($this->areTodayHolidays($bundesland)) {
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

            if ($this->matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
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

    public function holidaysEndInDays($bundesland)
    {
        // Get the current date
        $today = $this->getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');
        $bundeslandCode = $this->getBundeslandCode($bundesland);

        // Prüfen, ob heute überhaupt Ferien sind
        if (!$this->areTodayHolidays($bundesland)) {
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

            if ($this->matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
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

    private function matchesBundesland($subdivisions, $bundeslandCode)
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

    /**
     * @return mixed[]
     */
    private function getBundslandSlugMap(): array
    {
        return collect(config('holiday.states'))->pluck('iso', 'kuerzel')->toArray();
    }
}
