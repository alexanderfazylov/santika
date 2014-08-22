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
            [['id', 'object_id', 'upload_id'], 'integer'],
            [['name', 'object_name'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        /**
         * @TODO отрефакторить
         */
        if ($this->type == static::TYPE_LINE) {
            $query->joinWith(['line']);
            $query->andFilterWhere(['like', 'line.name', $this->object_name]);
            $dataProvider->sort->attributes['object_name'] = [
                'asc' => ['line.name' => SORT_ASC],
                'desc' => ['line.name' => SORT_DESC],
            ];
        }

        if ($this->type == static::TYPE_COLLECTION) {
            $query->joinWith(['collection']);
            $query->andFilterWhere(['like', 'collection.name', $this->object_name]);
            $dataProvider->sort->attributes['object_name'] = [
                'asc' => ['collection.name' => SORT_ASC],
                'desc' => ['collection.name' => SORT_DESC],
            ];
        }

//        if (!($this->load($params) && $this->validate())) {
//            return $dataProvider;
//        }

        $query->andFilterWhere([
            'id' => $this->id,
            'object_id' => $this->object_id,
            'upload_id' => $this->upload_id,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
