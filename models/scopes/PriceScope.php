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
     * Находит активный прайс
     * @return $this
     */
    public function active($shop_id)
    {
        /**
         * @TODO оптимизировать
         */
        $active_price = Price::find()
            ->byShop($shop_id)
            ->andWhere('start_date <= :start_date', [':start_date' => date('Y-m-d')])
            ->orderBy(['start_date ' => SORT_DESC])->one();

        $this->andWhere(['id' => is_null($active_price) ? null : $active_price->id]);
        return $this;
    }
}