<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LineProduct;

/**
 * LineProductSearch represents the model behind the search form about `app\models\LineProduct`.
 */
class LineProductSearch extends LineProduct
{
    public function rules()
    {
        return [
            [['id', 'line_id', 'product_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LineProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'line_id' => $this->line_id,
            'product_id' => $this->product_id,
        ]);

        return $dataProvider;
    }
}
