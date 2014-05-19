<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LineCategory;

/**
 * LineCategorySearch represents the model behind the search form about `app\models\LineCategory`.
 */
class LineCategorySearch extends LineCategory
{
    public function rules()
    {
        return [
            [['id', 'line_id', 'category_id'], 'integer'],
            [['line_name', 'category_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LineCategory::find();
        $query->joinWith(['line', 'category']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['line_name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['line.name' => SORT_ASC],
            'desc' => ['line.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['category_name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['category.name' => SORT_ASC],
            'desc' => ['category.name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'line_id' => $this->line_id,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'line.name', $this->line_name])
            ->andFilterWhere(['like', 'category.name', $this->category_name]);
        return $dataProvider;
    }
}
