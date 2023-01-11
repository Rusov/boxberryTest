<?php

namespace app\controllers;

use app\components\WeekCalcFacade;
use yii\base\Exception;
use yii\web\Controller;

class WeekController extends Controller
{
    /**
     * http://localhost:8080/week/get-tuesday-count?start=2000-01-01&end=2035-05-10
     * @throws Exception
     */
    public function actionGetTuesdayCount($start = '2000-01-01', $end = '2035-05-10')
    {
        $weekCalc = new WeekCalcFacade();

        //замер времени выполнения скрипта версии 1
        $microtimeOne = microtime(true);
        $resultOne = $weekCalc->getOneItemCount($start, $end, 'Tue', 1);
        $timeOne = round(microtime(true) - $microtimeOne, 4);

        //замер времени выполнения скрипта версии 2
        $microtimeTwo = microtime(true);
        $resultTwo = $weekCalc->getOneItemCount($start, $end, 'Tue', 2);
        $timeTwo = round(microtime(true) - $microtimeTwo, 4);

        // у меня получилось так: tasksFiles/task-3-my-result.png
        // 2ой вар, хоть и тяжелый в понимании, но работает крайне быстро
        // логика придумана лично
        return $this->asJson([
            1 => [
                'result' => $resultOne,
                'exec time' => $timeOne . ' sec',
            ],
            2 => [
                'result' => $resultTwo,
                'exec time' => $timeTwo . ' sec',
            ],
        ]);
    }
}
