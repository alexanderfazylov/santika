<?php

namespace app\controllers;

use app\models\Category;
use app\models\Collection;
use app\models\Interactive;
use app\models\InteractiveProduct;
use app\models\Line;
use app\models\Price;
use app\models\PriceProduct;
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


    public function actionLineProduct($url, $category_id = null, $collection_id = null)
    {
        $line = Line::find()->byUrl($url)->one();
        $query = Product::find()
            ->joinWith('photo')
            ->joinWith('lineProducts')
            ->andWhere(['line_id' => $line->id]);

        if (!empty($category_id)) {
            $query->andWhere(['category_id' => $category_id]);
        }

        if (!empty($collection_id)) {
            $query->andWhere(['collection_id' => $collection_id]);
        }
        $products = $query->all();

        $categories = Category::find()
            ->joinWith('lineCategories')
            ->andWhere(['line_id' => $line->id])
            ->all();

        $collections = Category::find()
            ->joinWith('lineCategories')
            ->andWhere(['line_id' => $line->id])
            ->all();
        return $this->render('line_product', [
            'products' => $products,
            'line' => $line,
            'categories' => $categories,
            'category_id' => $category_id,
            'collections' => $collections,
            'collection_id' => $collection_id,
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
        $product = Product::find()
            ->joinWith('photoGalleries')
            ->byUrl($url)->one();

        $shop_id = $line->shop_id;
        $price = Price::find()->active($shop_id, Price::TYPE_PRODUCT)->one();

        $price_product = PriceProduct::findOne([
            'price_id' => $price->id,
            'product_id' => $product->id,
        ]);

        $other_products = Product::find()
            ->joinWith('photo')
            ->joinWith('lineProducts')
            ->andWhere(['line_id' => $line->id])
            ->andWhere(['NOT IN', 'product.id', $product->id])
            ->limit(10)
            ->all();

        return $this->render('product', [
            'line' => $line,
            'category' => $category,
            'product' => $product,
            'price_product' => $price_product,
            'other_products' => $other_products,
        ]);
    }
}
