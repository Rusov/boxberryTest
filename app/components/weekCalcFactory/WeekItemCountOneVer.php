<?php

declare(strict_types=1);

namespace app\components\weekCalcFactory;

class WeekItemCountOneVer implements IWeekItemCount
{
    public function get($start, $end, $item): int
    {
        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);

        $days = $endDate->diff($startDate)->days;

        $cnt = 0;
        for ($i = 0; $i <= $days; $i++) {
            $iDate = (clone $startDate)->modify("+ $i days");
            $IItem = $iDate->format('D');
            if ($IItem === $item) {
                $cnt++;
            }
        }

        return $cnt;
    }
}
