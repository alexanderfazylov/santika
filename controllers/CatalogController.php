<?php

namespace app\controllers;

use app\models\Category;
use app\models\Collection;
use app\models\Interactive;
use app\models\InteractiveProduct;
use app\models\Line;
use app\models\PhotoGallery;
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
            'lines' => $lines,
            'shop_id' => $shop_id,
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


    public function actionLineProduct($line_url, $category_url = null, $collection_url = null)
    {
        $line = Line::find()->byUrl($line_url)->one();
        $shop_id = $line->shop_id;

        $query = Product::find()
            ->published()
            ->joinWith('photo')
            ->joinWith('lineProducts')
            ->andWhere(['line_id' => $line->id]);

        if (!empty($category_url)) {
            /**
             * @TODO переделать на join?
             */
            $category = Category::findOne(['url' => $category_url]);
            $query->andWhere(['category_id' => $category->id]);
        }

        if (!empty($collection_url)) {
            /**
             * @TODO переделать на join?
             */
            $collection = Collection::findOne(['url' => $collection_url]);
            $query->andWhere(['collection_id' => $collection->id]);
        }
        $products = $query->all();

        $categories = Category::find()
            ->joinWith('lineCategories')
            ->andWhere(['line_id' => $line->id])
            ->all();

        $collections = Collection::find()
            ->byShop($shop_id)
            ->all();

        return $this->render('line_product', [
            'products' => $products,
            'line' => $line,
            'categories' => $categories,
            'category_url' => $category_url,
            'collections' => $collections,
            'collection_url' => $collection_url,
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

    public function actionProduct($line_url, $url, $category_url = null, $color_id = null)
    {
        $line = Line::find()->byUrl($line_url)->one();
        $category = Category::find()->byUrl($category_url)->one();
        $product = Product::find()
            ->published()
            ->with([
                'productColors.color',
                'photoGalleries' => function ($q) use ($color_id) {
                        $q->andWhere(['type' => PhotoGallery::TYPE_PRODUCT]);
                        $q->andWhere(['color_id' => $color_id]);
                        $q->orderBy(['sort' => SORT_ASC]);
                    },
            ])
            ->byUrl($url)
            ->one();

        $shop_id = $line->shop_id;
        $price = Price::find()->active($shop_id, Price::TYPE_PRODUCT)->one();
        /**
         * @todo пернести в with?
         */
        $price_product = PriceProduct::findOne([
            'price_id' => $price->id,
            'product_id' => $product->id,
            'color_id' => $color_id,
        ]);

        $next_product = Product::find()
            ->published()
            ->joinWith('lineProducts')
            ->andWhere(['line_id' => $line->id])
            ->andWhere('product.id > ' . $product->id)
            ->one();
        $prev_product = Product::find()
            ->published()
            ->joinWith('lineProducts')
            ->andWhere(['line_id' => $line->id])
            ->andWhere('product.id < ' . $product->id)
            ->one();
        $other_products = Product::find()
            ->published()
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
            'prev_product' => $prev_product,
            'next_product' => $next_product,
            'color_id' => $color_id,
        ]);
    }
}
