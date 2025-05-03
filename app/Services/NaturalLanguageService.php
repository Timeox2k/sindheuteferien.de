<?php
/**
 * File: NaturalLanguageService.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Services;

class NaturalLanguageService
{
    public function getEndingInString(
        array $endData
    )
    {
        $prefixedName = $this->buildPrefixedName($endData['holiday_name']);
        $days = $endData['days'];

        if ($days === 0) {
            return sprintf("<span class='highlight'>%s</span> enden heute", $prefixedName);
        }

        if ($days === 1) {
            return sprintf("<span class='highlight'>%s</span> enden morgen", $prefixedName);
        }

        return sprintf("<span class='highlight'>%s</span> enden in %d Tagen", $prefixedName, $days);
    }

    public function getButNextAreStartingInString(
        array $startData
    )
    {
        $prefixedName = $this->buildPrefixedName($startData['holiday_name'], true);
        $days = $startData['days'];

        if ($days === 0) {
            return sprintf("… aber <span class='highlight'>%s</span> beginnen heute", $prefixedName);
        }

        if ($days === 1) {
            return sprintf("… aber <span class='highlight'>%s</span> beginnen morgen", $prefixedName);
        }

        return sprintf("… aber <span class='highlight'>%s</span> beginnen in %d Tagen", $prefixedName, $days);
    }

    /**
     * @param $holidayName
     * @param bool $lowercase
     * @return mixed|string
     */
    private function buildPrefixedName($holidayName, bool $lowercase = false): mixed
    {
        $prefixedName = $holidayName;
        $areHolidays = str_contains($holidayName, 'ferien') || str_contains($holidayName, 'tage');
        if ($areHolidays) {
            $prefixedName = ($lowercase ? "die " : "Die ") . $holidayName;
        }
        return $prefixedName;
    }
}
