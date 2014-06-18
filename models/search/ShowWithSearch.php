<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ShowWith;

/**
 * ShowWithSearch represents the model behind the search form about `app\models\ShowWith`.
 */
class ShowWithSearch extends ShowWith
{
    public function rules()
    {
        return [
            [['id', 'object_id', 'product_id', 'type', 'sort'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ShowWith::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'product_id' => $this->product_id,
            'type' => $this->type,
            'sort' => $this->sort,
        ]);

        return $dataProvider;
    }
}
