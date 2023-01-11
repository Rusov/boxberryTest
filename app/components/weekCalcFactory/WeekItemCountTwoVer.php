<?php

declare(strict_types=1);

namespace app\components\weekCalcFactory;

/**
 * Сложнее в понимании, но более быстрая.
 * Логику придумал сам
 */
class WeekItemCountTwoVer implements IWeekItemCount
{

    public function get($start, $end, $item): int
    {
        $cnt = 0;
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);

        $days = $endDate->diff($startDate)->days + 1;
        $weeks = (int) floor($days / 7);
        $remainingDays = $days - $weeks * 7;

        if ($remainingDays === 0) {
            return $weeks;
        }

        $cnt += $weeks;
        for ($i = 0; $i < $remainingDays; $i++) {
            $iDate = (clone $endDate)->modify("- $i days");
            $IItem = $iDate->format('D');
            if ($IItem === $item) {
                $cnt++;
            }
        }

        return $cnt;
    }
}
