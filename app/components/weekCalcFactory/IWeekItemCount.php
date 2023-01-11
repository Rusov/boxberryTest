<?php

declare(strict_types=1);

namespace app\components\weekCalcFactory;

use Exception;

interface IWeekItemCount
{
    /**
     * @throws Exception
     */
    public function get($start, $end, $item): int;
}
