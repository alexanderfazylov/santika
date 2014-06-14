<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product
{
    public $shop_name;
    public $category_name;
    public $collection_name;

    public function rules()
    {
        return [
            [['id', 'shop_id', 'collection_id', 'category_id', 'manual_id', 'color_id', 'drawing_id', 'length', 'width', 'height', 'is_promotion', 'is_published'], 'integer'],
            [['article', 'name', 'description', 'url', 'meta_title', 'meta_description', 'meta_keywords', 'shop_name', 'category_name', 'collection_name',], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find();
        $query->joinWith(['category', 'collection']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        $dataProvider->sort->attributes['shop_name'] = [
//            'asc' => ['shop.name' => SORT_ASC],
//            'desc' => ['shop.name' => SORT_DESC],
//        ];

        $dataProvider->sort->attributes['collection_name'] = [
            'asc' => ['collection.name' => SORT_ASC],
            'desc' => ['collection.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['category_name'] = [
            'asc' => ['category.name' => SORT_ASC],
            'desc' => ['category.name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'collection_id' => $this->collection_id,
            'category_id' => $this->category_id,
            'manual_id' => $this->manual_id,
            'color_id' => $this->color_id,
            'drawing_id' => $this->drawing_id,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'is_promotion' => $this->is_promotion,
            'is_published' => $this->is_published,
        ]);

        $query->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', Product::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', Product::tableName() . '.url', $this->url])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
//            ->andFilterWhere(['like', 'shop.name', $this->shop_name])
            ->andFilterWhere(['like', 'collection.name', $this->collection_name])
            ->andFilterWhere(['like', 'category.name', $this->category_name]);

        return $dataProvider;
    }
}
