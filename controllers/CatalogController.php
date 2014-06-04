<?php

namespace app\controllers;

use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\Product;
use Yii;
use yii\web\Controller;

class CatalogController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionLine($url)
    {
        $line = Line::find()->byUrl($url)->one();
        return $this->render('line', [
            'line' => $line,
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
