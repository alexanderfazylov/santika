<?php

namespace app\controllers;

use app\models\Category;
use app\models\Collection;
use app\models\Interactive;
use app\models\InteractiveProduct;
use app\models\Line;
use app\models\Product;
use app\models\Upload;
use Yii;
use yii\web\Controller;

class CatalogController extends Controller
{
    public function actionIndex()
    {
        $shop_id = 1;
        $lines = Line::find()->byShop($shop_id)->all();
        return $this->render('index', [
            'lines' => $lines
        ]);
    }

    public function actionLine($url)
    {
        $line = Line::find()->byUrl($url)->one();
        $intaractives = Interactive::find()
            ->joinWith(Upload::tableName())
            ->joinWith('interactiveProducts')
            ->andWhere(['line_id' => $line->id])
            ->all();
        return $this->render('line', [
            'line' => $line,
            'intaractives' => $intaractives,
        ]);
    }

    public function actionCollection($url)
    {

        $collection = Collection::find()->byUrl($url)->one();
        return $this->render('collection', [
            'collection' => $collection,
        ]);
    }

    public function actionCategory($line_url, $url)
    {
        $line = Line::find()->byUrl($line_url)->one();
        $category = Category::find()->byUrl($url)->one();
        return $this->render('category', [
            'line' => $line,
            'category' => $category,
        ]);
    }

    public function actionProduct($line_url, $url, $category_url = null)
    {
        $line = Line::find()->byUrl($line_url)->one();
        $category = Category::find()->byUrl($category_url)->one();
        $product = Product::find()->byUrl($url)->one();
        return $this->render('product', [
            'line' => $line,
            'category' => $category,
            'product' => $product,
        ]);
    }
}
