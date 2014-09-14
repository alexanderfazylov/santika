<?php

namespace app\controllers;

use app\components\ThemedController;
use app\models\Category;
use app\models\Collection;
use app\models\Interactive;
use app\models\Line;
use app\models\LineCategory;
use app\models\PhotoGallery;
use app\models\Price;
use app\models\PriceProduct;
use app\models\Product;
use app\models\Shop;
use app\models\ShowWith;
use app\models\Upload;
use Yii;
use yii\base\Exception;

class CatalogController extends ThemedController
{
    public function actionIndex()
    {
        $shop_id = Shop::getIdFromUrl();
        $lines = Line::find()->byShop($shop_id)->all();
        $collections = Collection::find()->byShop($shop_id)->andWhere(['show_in_catalog' => true])->all();
        return $this->render('index', [
            'lines' => $lines,
            'collections' => $collections,
            'shop_id' => $shop_id,
        ]);
    }

    public function actionLine($url)
    {
        $line = Line::find()->byUrl($url)->one();
        return $this->interactive($line, Interactive::TYPE_LINE);
    }


    public function actionLineProduct($line_url = null, $category_url = null, $collection_url = null, $article = null)
    {
        $shop_id = Shop::getIdFromUrl();

        $query = Product::find()
            ->published()
            ->joinWith('photo');

        $line = null;
        $category = null;
        $collection = null;
        if (!empty($line_url)) {
            $line = Line::find()->byUrl($line_url)->one();
            $query->joinWith('lineProducts')
                ->andWhere(['line_id' => $line->id]);
        }


        if (!empty($category_url)) {
            $category = Category::findOne(['url' => $category_url]);
            $query->andWhere(['category_id' => $category->id]);
        }

        if (!empty($collection_url)) {
            $collection = Collection::findOne(['url' => $collection_url]);
            $query->andWhere(['collection_id' => $collection->id]);
        }
        $products = $query->all();

//        $category_ids = LineCategory::categoryIdsByLine($line->id);
        /**
         * @TODO проверить рекурсию в категориях
         */
        $categories = Category::find()
            ->joinWith(['childs'
//            => function ($q) use ($category_ids) {
//                    //т.к. условие добавляется в общее where, то выберем детей из категорий в линии
//                    //и родителей без детей
////                    $q->orWhere(['childs.id' => $category_ids]);
////                    $q->orWhere(['childs.id' => null]);
//                },
            ])
            ->isParent()
//            ->andWhere([Category::tableName() . '.id' => $category_ids])
            ->all();

        $collections = Collection::find()
            ->joinWith(['childs' => function ($q) {
                    $q->from('collection childs');
                },
            ])
            ->byShop($shop_id)
            ->isParent()
            ->all();

        return $this->render('line_product', [
            'products' => $products,
            'line' => $line,
            'category' => $category,
            'collection' => $collection,
            'categories' => $categories,
            'collections' => $collections,
            'category_url' => $category_url,
            'collection_url' => $collection_url,
        ]);
    }

    public function actionCollection($url, $parent_url = null)
    {
        $collection = Collection::find()->byUrl($url)->one();
        return $this->interactive($collection, Interactive::TYPE_COLLECTION);
    }

    /**
     * Рисует страницу с интерактивными фото для линии или коллекции
     * @param $model
     * @param $type
     * @return string
     */
    public function interactive($model, $type)
    {
        $intaractives = Interactive::find()
            ->joinWith(Upload::tableName())
            ->joinWith('interactiveProducts.product')
            ->andWhere(['object_id' => $model->id])
            ->andWhere([Interactive::tableName() . '.type' => $type])
            ->all();
        return $this->render('interactive', [
            'model' => $model,
            'type' => $type,
            'intaractives' => $intaractives,
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

    public function actionProduct($url, $line_url = null, $category_url = null, $color_id = null)
    {
        $line = Line::find()->byUrl($line_url)->one();
        $shop_id = Shop::getIdFromUrl();
        $category = Category::find()->byUrl($category_url)->one();
        $product = Product::find()
            ->published()
            ->with([
                'productColors.color',
                'productInstallationProducts.installationProduct',
                'productInstallationProducts.installationProduct.photo',
                'photoGalleries' => function ($q) use ($color_id) {
                        $q->andWhere(['type' => PhotoGallery::TYPE_PRODUCT]);
                        $q->andWhere(['color_id' => $color_id]);
                        $q->orderBy(['sort' => SORT_ASC]);
                    },
            ])
            ->byUrl($url)
            ->one();

        if (is_null($product)) {
            throw new Exception('Товар не найден. Возможно он не опубликован');
        }

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
//            ->andWhere(['line_id' => $line->id])
            ->andWhere('product.id > ' . $product->id)
            ->one();
        $prev_product = Product::find()
            ->published()
            ->joinWith('lineProducts')
//            ->andWhere(['line_id' => $line->id])
            ->andWhere('product.id < ' . $product->id)
            ->one();
        $other_products = Product::find()
            ->published()
            ->joinWith('photo')
            ->joinWith(['showWith' => function ($q) use ($product) {
                    $q->andWhere([
                        'object_id' => $product->id,
                    ]);
                }
            ])
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

    public function actionProductInfo($product_id, $line_url = null)
    {
        Yii::$app->response->format = 'json';
        /**
         * @var Product $product
         */
        $product = Product::find()
            ->with(['photo', 'productColors.photo', 'productInstallations'])
            ->andWhere([Product::tableName() . '.id' => $product_id])
            ->one();
        if (!is_null($product)) {

            $colors = [];
            foreach ($product->productColors as $pc) {
                $colors[$pc->color_id] = $pc->color->name;
            }

            $installations = [];
            foreach ($product->productInstallations as $pi) {
                $installations[$pi->installation_id] = $pi->installation->name;
            }

            $shop_id = Shop::getIdFromUrl();
            $price = Price::find()->active($shop_id, Price::TYPE_PRODUCT)->one();
            $price_product = PriceProduct::findOne([
                'price_id' => $price->id,
                'product_id' => $product->id,
                'color_id' => key($colors) ? key($colors) : null, //@TODO берет цену первого покрытия
            ]);

            $product_info = [
                'name' => $product->name,
                'description' => $product->description,
                'article' => $product->article,
                'lwh' => $product->getLwh(),
                'color' => implode(', ', $colors),
                'photo' => !empty($product->photo) ? $product->photo->getFileShowUrl(Upload::SIZE_SQUARE_245) : '',
                'link' => (!empty($line_url) ? $product->createUrlByLine($line_url) : $product->canonical),
                'price' => !is_null($price_product) ? $price_product->cost_rub . ' р.' : '',
                'country' => 'Италия', //@TODO сделать через Shop
                'installation' => implode(', ', $installations)
            ];
            $result = ['status' => 'success', 'product' => $product_info];
        } else {
            $result = ['status' => 'error', 'message' => 'Товар не найден'];
        }
        return $result;
    }
}
