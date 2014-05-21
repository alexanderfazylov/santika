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
    public $line_name;
    public $product_name;

    public function rules()
    {
        return [
            [['id', 'line_id', 'product_id'], 'integer'],
            [['line_name', 'product_name'], 'safe'],
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

        $dataProvider->sort->attributes['line_name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['line.name' => SORT_ASC],
            'desc' => ['line.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['product_name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['product.name' => SORT_ASC],
            'desc' => ['product.name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'line_id' => $this->line_id,
            'product_id' => $this->product_id,
        ]);

        $query->andFilterWhere(['like', 'line.name', $this->line_name])
            ->andFilterWhere(['like', 'product.name', $this->product_name]);

        return $dataProvider;
    }
}
