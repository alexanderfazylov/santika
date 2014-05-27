<?php
/**
 * Created by PhpStorm.
 * User: KURT
 * Date: 24.05.14
 * Time: 23:04
 */

namespace app\models\scopes;


use app\models\Price;
use yii\db\ActiveQuery;

class PriceScope extends ActiveQuery
{
    /**
     * @param integer $shop_id
     * @return $this
     */
    public function byShop($shop_id)
    {
        $this->andWhere(['shop_id' => $shop_id]);
        return $this;
    }

    /**
     * Находит активный прайс по его типу
     * @param $shop_id
     * @param int $type
     * @return $this
     */
    public function active($shop_id, $type = Price::TYPE_PRODUCT)
    {
        /**
         * @TODO оптимизировать
         */
        $active_price = Price::find()
            ->byShop($shop_id)
            ->andWhere('start_date <= :start_date', [':start_date' => date('Y-m-d')])
            ->andWhere(['type' => $type])
            ->orderBy(['start_date' => SORT_DESC])->one();

        $this->andWhere(['id' => is_null($active_price) ? null : $active_price->id]);
        return $this;
    }
}