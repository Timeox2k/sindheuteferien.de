<?php
/**
 * File: HolidayService.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Services;

use App\Models\SchoolHoliday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HolidayService
{
    public function getNow(): Carbon
    {
        $request = app(Request::class);

        if ($request->has("now")) {
            try {
                return $request->date('now');
            } catch (\Exception $e) {
            }
        }

        return now();
    }

    public function getAllStates(): array
    {
        return config('holiday.states', []);
    }

    public function getStateBySlug(string $slug): ?array
    {
        return collect($this->getAllStates())->firstWhere('slug', $slug);
    }

    public function getStateByKuerzel(string $kuerzel): ?array
    {
        return collect($this->getAllStates())->firstWhere('kuerzel', strtolower($kuerzel));
    }

    /**
     * @param string $bundeslandSlugOrKuerzel
     * @return string The ISO 3166-2 code for the Bundesland
     */
    public function getBundeslandCode(string $bundeslandSlugOrKuerzel): string
    {
        $state = $this->getStateBySlug($bundeslandSlugOrKuerzel)
            ?? $this->getStateByKuerzel($bundeslandSlugOrKuerzel);

        if ($state) {
            return $state['iso'];
        }

        $bundeslandMap = $this->getBundeslandSlugMap();
        return Arr::get($bundeslandMap, $bundeslandSlugOrKuerzel, $bundeslandSlugOrKuerzel);
    }

    public function areTodayHolidays(string $bundesland): bool
    {
        $today = $this->getNow()->format('Y-m-d');
        $bundeslandCode = $this->getBundeslandCode($bundesland);

        $currentHolidays = SchoolHoliday::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        foreach ($currentHolidays as $holiday) {
            if ($holiday->nationwide || $this->matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                return true;
            }
        }

        return false;
    }

    public function getDaysToNextHolidays(string $bundesland, int $minDurationDays = 2): ?array
    {
        $today = $this->getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');
        $bundeslandCode = $this->getBundeslandCode($bundesland);

        if ($this->areTodayHolidays($bundesland)) {
            return null;
        }

        $nextHolidays = SchoolHoliday::whereDate('start_date', '>', $todayFormatted)
            ->whereRaw('julianday(end_date) - julianday(start_date) >= ?', [$minDurationDays - 1])
            ->orderBy('start_date', 'asc')
            ->get();

        if ($nextHolidays->isEmpty()) {
            return null;
        }

        $nextHoliday = null;
        foreach ($nextHolidays as $holiday) {
            if ($holiday->nationwide || $this->matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                $nextHoliday = $holiday;
                break;
            }
        }

        if (!$nextHoliday) {
            return null;
        }

        $nextHolidayStartDate = Carbon::parse($nextHoliday->start_date)->startOfDay();
        $nextHolidayEndDate = Carbon::parse($nextHoliday->end_date)->startOfDay();
        $daysUntilNextHoliday = $today->diffInDays($nextHolidayStartDate);
        $holidayDuration = $nextHolidayStartDate->diffInDays($nextHolidayEndDate) + 1;

        return [
            'days'         => (int)$daysUntilNextHoliday,
            'holiday_name' => $nextHoliday->name,
            'slug'         => $this->generateHolidaySlug($nextHoliday),
            'start_date'   => $nextHolidayStartDate->format('d.m.Y'),
            'end_date'     => $nextHolidayEndDate->format('d.m.Y'),
            'duration'     => $holidayDuration,
        ];
    }

    public function holidaysEndInDays(string $bundesland): ?array
    {
        $today = $this->getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');
        $bundeslandCode = $this->getBundeslandCode($bundesland);

        if (!$this->areTodayHolidays($bundesland)) {
            return null;
        }

        $currentHolidays = SchoolHoliday::whereDate('start_date', '<=', $todayFormatted)
            ->whereDate('end_date', '>=', $todayFormatted)
            ->orderBy('end_date', 'asc')
            ->get();

        if ($currentHolidays->isEmpty()) {
            return null;
        }

        $currentHoliday = null;
        foreach ($currentHolidays as $holiday) {
            if ($holiday->nationwide || $this->matchesBundesland($holiday->subdivisions, $bundeslandCode)) {
                $currentHoliday = $holiday;
                break;
            }
        }

        if (!$currentHoliday) {
            return null;
        }

        $holidayEndDate = Carbon::parse($currentHoliday->end_date)->startOfDay();
        $daysUntilHolidayEnd = $today->diffInDays($holidayEndDate);

        return [
            'days'         => (int)$daysUntilHolidayEnd,
            'holiday_name' => $currentHoliday->name,
            'slug'         => $this->generateHolidaySlug($currentHoliday),
            'start_date'   => Carbon::parse($currentHoliday->start_date)->format('d.m.Y'),
            'end_date'     => $holidayEndDate->format('d.m.Y'),
        ];
    }

    public function getTodayHolidayRadar(): array
    {
        $today = $this->getNow()->startOfDay();
        $todayFormatted = $today->format('Y-m-d');
        $allStates = $this->getAllStates();

        $endingToday = [];
        $startingToday = [];
        $activeToday = [];

        $currentHolidays = SchoolHoliday::whereDate('start_date', '<=', $todayFormatted)
            ->whereDate('end_date', '>=', $todayFormatted)
            ->get();

        foreach ($allStates as $state) {
            foreach ($currentHolidays as $h) {
                if ($h->nationwide || $this->matchesBundesland($h->subdivisions, $state['iso'])) {
                    $endDate = Carbon::parse($h->end_date)->startOfDay();
                    $startDate = Carbon::parse($h->start_date)->startOfDay();

                    if ($endDate->isSameDay($today)) {
                        $endingToday[] = [
                            'state'        => $state,
                            'holiday_name' => $h->name,
                            'end_date'     => $endDate->format('d.m.Y'),
                            'slug'         => $this->generateHolidaySlug($h),
                        ];
                    }

                    if ($startDate->isSameDay($today)) {
                        $startingToday[] = [
                            'state'        => $state,
                            'holiday_name' => $h->name,
                            'start_date'   => $startDate->format('d.m.Y'),
                            'slug'         => $this->generateHolidaySlug($h),
                        ];
                    }

                    $activeToday[] = [
                        'state'        => $state,
                        'holiday_name' => $h->name,
                        'end_date'     => $endDate->format('d.m.Y'),
                        'days_left'    => (int)$today->diffInDays($endDate),
                        'slug'         => $this->generateHolidaySlug($h),
                    ];
                    break;
                }
            }
        }

        $nextEnding = null;
        if (empty($endingToday) && !empty($activeToday)) {
            $nextEnding = collect($activeToday)->sortBy('days_left')->first();
        }

        $nextStarting = null;
        if (empty($startingToday)) {
            $futureHolidays = SchoolHoliday::whereDate('start_date', '>', $todayFormatted)
                ->orderBy('start_date', 'asc')
                ->get();

            foreach ($futureHolidays as $fh) {
                $startDate = Carbon::parse($fh->start_date)->startOfDay();
                foreach ($allStates as $state) {
                    if ($fh->nationwide || $this->matchesBundesland($fh->subdivisions, $state['iso'])) {
                        $nextStarting = [
                            'state'        => $state,
                            'holiday_name' => $fh->name,
                            'start_date'   => $startDate->format('d.m.Y'),
                            'days_until'   => (int)$today->diffInDays($startDate),
                            'slug'         => $this->generateHolidaySlug($fh),
                        ];
                        break 2;
                    }
                }
            }
        }

        return [
            'today'          => $today,
            'ending_today'   => $endingToday,
            'starting_today' => $startingToday,
            'active_today'   => $activeToday,
            'next_ending'    => $nextEnding,
            'next_starting'  => $nextStarting,
        ];
    }

    public function generateHolidaySlug(SchoolHoliday $holiday): string
    {
        $nameSlug = Str::slug($holiday->name);
        $year = Carbon::parse($holiday->start_date)->year;
        return "{$nameSlug}-{$year}";
    }

    public function getHolidaysForBundesland(string $bundesland, ?int $year = null): Collection
    {
        $bundeslandCode = $this->getBundeslandCode($bundesland);
        $today = $this->getNow()->startOfDay();

        $query = SchoolHoliday::query();

        if ($year !== null) {
            $query->where(function ($q) use ($year) {
                $q->whereYear('start_date', $year)
                  ->orWhereYear('end_date', $year);
            });
        }

        $holidays = $query->orderBy('start_date', 'asc')->get();

        return $holidays->filter(function (SchoolHoliday $holiday) use ($bundeslandCode) {
            return $holiday->nationwide || $this->matchesBundesland($holiday->subdivisions, $bundeslandCode);
        })->map(function (SchoolHoliday $holiday) use ($today) {
            $startDate = Carbon::parse($holiday->start_date)->startOfDay();
            $endDate = Carbon::parse($holiday->end_date)->startOfDay();
            $duration = $startDate->diffInDays($endDate) + 1;

            $status = 'upcoming';
            $daysDiff = $today->diffInDays($startDate, false);

            if ($today->between($startDate, $endDate)) {
                $status = 'active';
            } elseif ($today->gt($endDate)) {
                $status = 'past';
            }

            return [
                'id'            => $holiday->id,
                'name'          => $holiday->name,
                'slug'          => $this->generateHolidaySlug($holiday),
                'year'          => $startDate->year,
                'start_date'    => $startDate->format('d.m.Y'),
                'end_date'      => $endDate->format('d.m.Y'),
                'start_date_iso'=> $startDate->format('Y-m-d'),
                'end_date_iso'  => $endDate->format('Y-m-d'),
                'duration'      => $duration,
                'start_kw'      => $startDate->isoWeek(),
                'end_kw'        => $endDate->isoWeek(),
                'status'        => $status,
                'days_diff'     => (int)$daysDiff,
            ];
        })->values();
    }

    public function getHolidaysGroupedByYear(string $bundesland, array $years = [2025, 2026, 2027]): array
    {
        $grouped = [];
        foreach ($years as $yr) {
            $holidays = $this->getHolidaysForBundesland($bundesland, $yr);
            if ($holidays->isNotEmpty()) {
                $grouped[$yr] = $holidays;
            }
        }
        return $grouped;
    }

    public function getHolidayDetail(string $bundesland, string $holidaySlug): ?array
    {
        $state = $this->getStateBySlug($bundesland) ?? $this->getStateByKuerzel($bundesland);
        if (!$state) {
            return null;
        }

        $today = $this->getNow()->startOfDay();
        $allHolidays = $this->getHolidaysForBundesland($state['kuerzel']);

        $match = $allHolidays->firstWhere('slug', $holidaySlug);
        if (!$match) {
            return null;
        }

        $startDate = Carbon::createFromFormat('d.m.Y', $match['start_date'])->startOfDay();
        $endDate = Carbon::createFromFormat('d.m.Y', $match['end_date'])->startOfDay();

        $baseName = Str::slug($match['name']);
        $allYearsSameHoliday = $allHolidays->filter(function ($h) use ($baseName) {
            return Str::startsWith($h['slug'], $baseName . '-');
        })->sortBy('year')->values();

        $otherStatesComparison = [];
        $targetYear = $match['year'];
        foreach ($this->getAllStates() as $s) {
            if ($s['kuerzel'] === $state['kuerzel']) {
                continue;
            }
            $stateHolidays = $this->getHolidaysForBundesland($s['kuerzel'], $targetYear);
            $foundSame = $stateHolidays->first(function ($h) use ($baseName) {
                return Str::startsWith($h['slug'], $baseName . '-');
            });

            if ($foundSame) {
                $otherStatesComparison[] = [
                    'state_name' => $s['name'],
                    'state_slug' => $s['slug'],
                    'state_short'=> $s['short'],
                    'start_date' => $foundSame['start_date'],
                    'end_date'   => $foundSame['end_date'],
                    'duration'   => $foundSame['duration'],
                    'slug'       => $foundSame['slug'],
                ];
            }
        }

        $nextHolidayInState = $allHolidays->first(function ($h) use ($startDate) {
            $hStart = Carbon::createFromFormat('d.m.Y', $h['start_date'])->startOfDay();
            return $hStart->gt($startDate);
        });

        $prevHolidayInState = $allHolidays->filter(function ($h) use ($startDate) {
            $hStart = Carbon::createFromFormat('d.m.Y', $h['start_date'])->startOfDay();
            return $hStart->lt($startDate);
        })->last();

        return [
            'state'             => $state,
            'holiday'           => $match,
            'start_carbon'      => $startDate,
            'end_carbon'        => $endDate,
            'days_until_start'  => (int)$today->diffInDays($startDate, false),
            'days_until_end'    => (int)$today->diffInDays($endDate, false),
            'next_holiday'      => $nextHolidayInState,
            'prev_holiday'      => $prevHolidayInState,
            'other_years'       => $allYearsSameHoliday,
            'other_states'      => $otherStatesComparison,
        ];
    }

    public function getAvailableYears(): array
    {
        return SchoolHoliday::selectRaw('strftime("%Y", start_date) as year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->map(fn($y) => (int)$y)
            ->filter(fn($y) => $y > 0)
            ->values()
            ->toArray();
    }

    public function getHolidaySlugsForYear(int $year): array
    {
        $urls = [];
        $states = $this->getAllStates();

        foreach ($states as $state) {
            $holidays = $this->getHolidaysForBundesland($state['kuerzel'], $year);
            foreach ($holidays as $holiday) {
                $urls[] = [
                    'state_slug'   => $state['slug'],
                    'holiday_slug' => $holiday['slug'],
                    'year'         => $holiday['year'],
                ];
            }
        }

        return $urls;
    }

    public function getAllHolidaySlugsForSitemap(): array
    {
        $urls = [];
        $states = $this->getAllStates();

        foreach ($states as $state) {
            $holidays = $this->getHolidaysForBundesland($state['kuerzel']);
            foreach ($holidays as $holiday) {
                $urls[] = [
                    'state_slug'   => $state['slug'],
                    'holiday_slug' => $holiday['slug'],
                    'year'         => $holiday['year'],
                ];
            }
        }

        return $urls;
    }

    public function matchesBundesland($subdivisions, string $bundeslandCode): bool
    {
        if (is_string($subdivisions)) {
            $subdivisions = json_decode($subdivisions, true);
        }

        if (is_array($subdivisions)) {
            $jsonString = json_encode($subdivisions);
            return strpos($jsonString, '"code":"' . $bundeslandCode . '"') !== false ||
                   strpos($jsonString, '"code":"' . $bundeslandCode . '-') !== false;
        }

        return false;
    }

    private function getBundeslandSlugMap(): array
    {
        return collect(config('holiday.states'))->pluck('iso', 'kuerzel')->toArray();
    }
}
