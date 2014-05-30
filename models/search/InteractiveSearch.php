<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Interactive;

/**
 * InteractiveSearch represents the model behind the search form about `app\models\Interactive`.
 */
class InteractiveSearch extends Interactive
{
    public function rules()
    {
        return [
            [['id', 'line_id', 'upload_id'], 'integer'],
            [['name', 'line_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Interactive::find();
        $query->joinWith(['line']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['line_name'] = [
            'asc' => ['line.name' => SORT_ASC],
            'desc' => ['line.name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'line_id' => $this->line_id,
            'upload_id' => $this->upload_id,
        ]);

        $query->andFilterWhere(['like', 'line.name', $this->line_name]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
