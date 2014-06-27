<?php

namespace app\modules\admin\controllers;

use app\models\Currency;
use app\models\Price;
use app\models\Product;
use app\models\Shop;
use Yii;
use app\models\PriceProduct;
use app\models\search\PriceProductSearch;
use yii\base\DynamicModel;
use yii\base\Exception;
use app\modules\admin\components\AdminController;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PriceProductController implements the CRUD actions for PriceProduct model.
 */
class PriceProductController extends AdminController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PriceProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PriceProductSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single PriceProduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PriceProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PriceProduct;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PriceProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PriceProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PriceProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PriceProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PriceProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Выводит все товары с текущими ценами
     * @param int $price_id
     * @return mixed
     */
    public function actionProduct($price_id = null)
    {
        /**
         * @TODO сделать правильно
         */
        $shop_id = Shop::getIdFromUrl();
        $price = Price::findOne($price_id);
        $filter_model = new DynamicModel([
            'product_id',
            'name',
            'article',
            'color_id',
            'cost_eur',
            'cost_rub',
        ]);
        $filter_model->addRule(['name', 'article'], 'string', ['max' => 128]);
//        $filter_model->addRule(['cost_eur', 'cost_rub'], 'number');;
        $filter_model->load(Yii::$app->request->getQueryParams());

        if (empty($price)) {
//            throw new Exception('Указанный прайс не найден');
            $array = [];
        } else {
            if ($price->type == Price::TYPE_PRODUCT) {
                $query = Product::find()
                    ->byShop($shop_id)
                    ->with([
                        'priceProduct' => function ($q) use ($price_id) {
                                //что бы выбрать только из нужного прайс-листа
                                $q->andWhere(['price_id' => $price_id, 'color_id' => null]);
                            },
                        'productColors.color',
                        'productColors.priceProducts' => function ($q) use ($price_id) {
                                //что бы выбрать только из нужного прайс-листа
                                $q->andWhere(['price_id' => $price_id]);
                            },
                    ]);

                $query->andFilterWhere(['like', 'article', $filter_model->article])
                    ->andFilterWhere(['like', 'name', $filter_model->name])
                    ->andFilterWhere(['productColors.color_id' => $filter_model->color_id])
//                        ->andFilterWhere(['like',  'cost_eur', $filter_model->cost_eur])
//                        ->andFilterWhere(['like',  'cost_rub', $filter_model->cost_rub])
                ;
                $products = $query->all();

                $array = [];
                foreach ($products as $product) {
                    if (!empty($product->productColors)) {
                        foreach ($product->productColors as $product_color) {
                            //у товара есть покрытий, поэтому цена берется из каждого покрытия отдельно
                            $price_exist = !empty($product_color->priceProducts);
                            /** @var PriceProduct $price_product */
                            $price_product = ($price_exist) ? $product_color->priceProducts[0] : null;

                            $array[] = [
                                'product_id' => $product->id,
                                'name' => $product->name,
                                'article' => $product->article,
                                'color' => $product_color->color->name,
                                'color_id' => $product_color->color->id,
                                'cost_eur' => $price_exist ? $price_product->cost_eur : '0.00',
                                'cost_rub' => $price_exist ? $price_product->cost_rub : '0.00',
                            ];
                        }
                    } else {
                        //у товара нет покрытий, поэтому цена берется из товара
                        $price_exist = !empty($product->priceProduct);
                        /** @var PriceProduct $price_product */
                        $price_product = ($price_exist) ? $product->priceProduct[0] : null;

                        $array[] = [
                            'product_id' => $product->id,
                            'name' => $product->name,
                            'article' => $product->article,
                            'color' => '',
                            'color_id' => null,
                            'cost_eur' => $price_exist ? $price_product->cost_eur : '0.00',
                            'cost_rub' => $price_exist ? $price_product->cost_rub : '0.00',
                        ];
                    }
                }
            } else {
                /**
                 * @TODO для услуг сделать другую вьюху
                 */
                $array = [];
            }
        }

        $data_provider = new ArrayDataProvider([
            'allModels' => $array,
            'sort' => [
                'attributes' => ['product_id', 'name', 'color', 'article', 'cost_eur', 'cost_rub'],
            ],
        ]);

        return $this->render('product', [
            'shop_id' => $shop_id,
            'price_id' => $price_id,
            'data_provider' => $data_provider,
            'filter_model' => $filter_model,
        ]);
    }

    public function actionSaveAjax()
    {
        Yii::$app->response->format = 'json';

        if (isset($_POST['price_id']) && isset($_POST['product_id']) && isset($_POST['cost_eur'])) {
            $color_id = isset($_POST['color_id']) ? $_POST['color_id'] : null;
            $model = PriceProduct::findOne([
                'price_id' => $_POST['price_id'],
                'product_id' => $_POST['product_id'],
                'color_id' => $color_id,
            ]);

            if (empty($model)) {
                $model = new PriceProduct();
                $model->price_id = $_POST['price_id'];
                $model->product_id = $_POST['product_id'];
                $model->color_id = $color_id;
            }

            $model->cost_eur = $_POST['cost_eur'];
            $curs_eur = Currency::getEurValue();
            $model->cost_rub = $_POST['cost_eur'] * $curs_eur;

            if ($model->save()) {
                return ['status' => 'success', 'cost_rub' => $model->cost_rub];
            } else {
                return ['status' => 'error', 'messages' => $model->getErrors()];
            }
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionDeleteAjax()
    {
        Yii::$app->response->format = 'json';

        if (isset($_POST['price_id']) && isset($_POST['product_id'])) {
            $color_id = isset($_POST['color_id']) ? $_POST['color_id'] : null;
            $model = PriceProduct::findOne([
                'price_id' => $_POST['price_id'],
                'product_id' => $_POST['product_id'],
                'color_id' => $color_id,
            ]);

            if (!empty($model)) {
                $model->delete();
            }

            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }
}
