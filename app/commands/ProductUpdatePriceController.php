<?php

declare(strict_types=1);

namespace app\commands;

use app\components\product\PriceBulkUpdate;
use Exception;


/**
 * cron * /2 * * * php app/yii product-update-price/update-price
 */
class ProductUpdatePriceController
{
    /**
     * @throws Exception
     */
    public function actionUpdatePrice()
    {
        $updater = new PriceBulkUpdate();

        $updater->run();
    }

}
