<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Currency;
use app\models\Price;
use app\models\PriceProduct;
use app\models\Shop;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SantikaController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    public function actionUpdatePrice()
    {
        $shop_id = Shop::getIdFromUrl();
        $curs_eur = Currency::getEurValue();

        $price = Price::find()->active($shop_id, Price::TYPE_PRODUCT)->one();
        if (!is_null($price)) {

            $price_products = PriceProduct::findAll(['price_id' => $price->id]);
            foreach ($price_products as $price_product) {
                $price_product->cost_rub = $price_product->cost_eur * $curs_eur;
                $price_product->save();
            }
        }
    }
}
