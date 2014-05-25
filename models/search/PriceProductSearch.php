<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PriceProduct;

/**
 * PriceProductSearch represents the model behind the search form about `app\models\PriceProduct`.
 */
class PriceProductSearch extends PriceProduct
{
    public function rules()
    {
        return [
            [['id', 'price_id', 'product_id'], 'integer'],
            [['cost_eur', 'cost_rub'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PriceProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'price_id' => $this->price_id,
            'product_id' => $this->product_id,
            'cost_eur' => $this->cost_eur,
            'cost_rub' => $this->cost_rub,
        ]);

        return $dataProvider;
    }
}
