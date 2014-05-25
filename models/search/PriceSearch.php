<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Price;

/**
 * PriceSearch represents the model behind the search form about `app\models\Price`.
 */
class PriceSearch extends Price
{
    public function rules()
    {
        return [
            [['id', 'shop_id', 'type'], 'integer'],
            [['start_date','typeText'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Price::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'start_date' => $this->start_date,
            'type' => $this->type,
        ]);

        return $dataProvider;
    }
}
