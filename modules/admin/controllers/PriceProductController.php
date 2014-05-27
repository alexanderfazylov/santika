<?php

namespace app\modules\admin\controllers;

use app\models\Currency;
use app\models\Price;
use app\models\Product;
use Yii;
use app\models\PriceProduct;
use app\models\search\PriceProductSearch;
use yii\base\Exception;
use app\modules\admin\components\AdminController;
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
    public function actionProduct($price_id)
    {
        $shop_id = 1;
        $price = Price::findOne($price_id);
        if (empty($price)) {
//            throw new Exception('Указанный прайс не найден');
            $products = [];
        } else {
            if ($price->type == Price::TYPE_PRODUCT) {
                $products = Product::find()
                    ->byShop($shop_id)
                    ->with([
                        'priceProduct' => function ($q) use ($price_id) {
                                $q->andWhere(['price_id' => $price_id]);
                            }
                    ])
                    ->all();
            } else {
                /**
                 * @TODO для товаров сделать другую вьюху
                 */
                $products = [];
            }
        }
        return $this->render('product', [
            'products' => $products,
            'shop_id' => $shop_id,
            'price_id' => $price_id,
        ]);
    }

    public function actionSaveAjax()
    {
        Yii::$app->response->format = 'json';

        if (isset($_POST['price_id']) && isset($_POST['product_id']) && isset($_POST['cost_eur'])) {
            $model = PriceProduct::findOne(['price_id' => $_POST['price_id'], 'product_id' => $_POST['product_id']]);

            if (empty($model)) {
                $model = new PriceProduct();
                $model->price_id = $_POST['price_id'];
                $model->product_id = $_POST['product_id'];
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
            $model = PriceProduct::findOne(['price_id' => $_POST['price_id'], 'product_id' => $_POST['product_id']]);

            if (!empty($model)) {
                $model->delete();
            }

            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }
}
