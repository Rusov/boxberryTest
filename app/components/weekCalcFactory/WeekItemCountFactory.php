<?php

declare(strict_types=1);

namespace app\components\weekCalcFactory;

use yii\base\Exception;

class WeekItemCountFactory
{
    /**
     * @throws Exception
     */
    public function getVer(int $v): IWeekItemCount
    {
        switch ($v) {
            case 1:
                $weekItemCount = new WeekItemCountOneVer();
                break;

            case 2:
                $weekItemCount = new WeekItemCountTwoVer();
                break;

            default:
                throw new Exception('Неверная версия WeekItemCount');
        }

        return $weekItemCount;
    }
}
