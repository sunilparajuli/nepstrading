<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Map of Australian state names to their abbreviations.
     */
    protected static $stateMap = [
        'victoria' => 'VIC',
        'vic' => 'VIC',
        'new south wales' => 'NSW',
        'nsw' => 'NSW',
        'queensland' => 'QLD',
        'qld' => 'QLD',
        'western australia' => 'WA',
        'wa' => 'WA',
        'south australia' => 'SA',
        'sa' => 'SA',
        'tasmania' => 'TAS',
        'tas' => 'TAS',
        'australian capital territory' => 'ACT',
        'act' => 'ACT',
        'northern territory' => 'NT',
        'nt' => 'NT',
    ];

    /**
     * Normalize a state name to its standard abbreviation.
     */
    public static function normalizeState($state)
    {
        if (!$state) return null;
        $state = strtolower(trim($state));
        return self::$stateMap[$state] ?? strtoupper($state);
    }

    /**
     * Check if a postcode matches a target pattern (exact, range, or wildcard).
     */
    public static function matchPostcode($userPostcode, $targetPattern)
    {
        $userPostcode = trim($userPostcode);
        $targetPattern = trim($targetPattern);

        // 1. Wildcard match (e.g. 6014*)
        if (str_contains($targetPattern, '*')) {
            $prefix = str_replace('*', '', $targetPattern);
            return str_starts_with($userPostcode, $prefix);
        }

        // 2. Range match (e.g. 6000-6005)
        if (str_contains($targetPattern, '-')) {
            list($start, $end) = explode('-', $targetPattern);
            $start = (int)trim($start);
            $end = (int)trim($end);
            $userValue = (int)$userPostcode;
            return ($userValue >= $start && $userValue <= $end);
        }

        // 3. Exact match
        return $userPostcode === $targetPattern;
    }
}
