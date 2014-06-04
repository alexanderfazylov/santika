<?php
/**
 * Created by PhpStorm.
 * User: KURT
 * Date: 19.05.14
 * Time: 0:07
 */

namespace app\models\scopes;


use yii\db\ActiveQuery;
use yii\db\Query;

class ProductScope extends ActiveQuery
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
     * @param integer $line_id
     * @return $this
     */
    public function byLine($line_id)
    {
        /**
         * @TODO оптимизировать?
         */
        $query_for_id = (new Query)->select('product_id')->from('line_product')->where(['line_id' => $line_id]);
        $this->andWhere(['id' => $query_for_id]);
        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function byUrl($url)
    {
        $this->andWhere(['url' => $url]);
        return $this;
    }
} 