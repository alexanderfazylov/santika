<?php

namespace app\modules\admin\controllers;

use app\models\InteractiveProduct;
use app\models\Shop;
use app\modules\admin\components\AdminController;
use Yii;
use app\models\Interactive;
use app\models\search\InteractiveSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InteractiveController implements the CRUD actions for Interactive model.
 */
class InteractiveController extends AdminController
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
     * Lists all Interactive models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InteractiveSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Interactive model.
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
     * Creates a new Interactive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Interactive;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $shop_id = Shop::getIdFromUrl();
            return $this->render('create', [
                'model' => $model,
                'shop_id' => $shop_id,
            ]);
        }
    }

    /**
     * Updates an existing Interactive model.
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
            $shop_id = Shop::getIdFromUrl();
            return $this->render('update', [
                'model' => $model,
                'shop_id' => $shop_id,
            ]);
        }
    }

    /**
     * Deletes an existing Interactive model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = 'json';
        $model = $this->findModel($id);
        if ($model->delete()) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'messages' => $model->getErrors()];
        }
    }

    /**
     * Finds the Interactive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Interactive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interactive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @TODO переименовать
     */
    public function actionProduct($id)
    {
        $shop_id = Shop::getIdFromUrl();
        $interactive = $this->findModel($id);
        return $this->render('product', [
                'shop_id' => $shop_id,
                'interactive' => $interactive
            ]
        );
    }

    public function actionGetProducts()
    {
        Yii::$app->response->format = 'json';

        if (isset($_POST['interactive_id'])) {
            $intaractive_products = InteractiveProduct::find()
                ->joinWith('product')
                ->andWhere(['interactive_id' => $_POST['interactive_id']])
                ->all();
            $products = [];
            foreach ($intaractive_products as $ip) {
                $data = [
                    'id' => $ip->id,
                    'product_id' => $ip->product_id,
                    'product_name' => $ip->product->name,
                    'left_percent' => $ip->left,
                    'top_percent' => $ip->top,
                ];
                $products[] = $data;
            }
            return [
                'status' => 'success',
                'products' => $products
            ];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionSaveProducts()
    {
        Yii::$app->response->format = 'json';

        if (isset($_POST['InteractiveProduct']) || isset($_POST['InteractiveProductDeleted'])) {

            if (isset($_POST['InteractiveProduct'])) {

                foreach ($_POST['InteractiveProduct'] as $intaractive_product) {

                    if (!empty($intaractive_product['id'])) {
                        $ip = InteractiveProduct::findOne($intaractive_product['id']);
                    } else {
                        $ip = new InteractiveProduct();
                    }
                    $ip->load($intaractive_product, '');

                    if (!$ip->save()) {
                        /**
                         * @TODO обработать ошибку
                         */
//                        return ['status' => 'error', 'messages' => $ip->getErrors()];
                    }
                }
            }

            if (isset($_POST['InteractiveProductDeleted'])) {

                foreach ($_POST['InteractiveProductDeleted'] as $id) {
                    $ip = InteractiveProduct::findOne($id);
                    $ip->delete();
                }
            }
            return [
                'status' => 'success',
            ];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }
}
