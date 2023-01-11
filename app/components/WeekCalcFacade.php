<?php

declare(strict_types=1);

namespace app\components;

use app\components\weekCalcFactory\WeekItemCountFactory;
use yii\base\Exception;

class WeekCalcFacade
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getOneItemCount($start, $end, $item, int $v): int
    {
        $factory = new WeekItemCountFactory();

        return $factory->getVer($v)->get($start, $end, $item);
    }
}
