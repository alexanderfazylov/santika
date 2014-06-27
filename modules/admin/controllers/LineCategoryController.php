<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use app\models\Shop;
use Yii;
use app\models\LineCategory;
use app\models\search\LineCategorySearch;
use yii\helpers\ArrayHelper;
use app\modules\admin\components\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LineCategoryController implements the CRUD actions for LineCategory model.
 */
class LineCategoryController extends AdminController
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
     * Lists all LineCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LineCategorySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single LineCategory model.
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
     * Creates a new LineCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LineCategory;

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
     * Updates an existing LineCategory model.
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
     * Deletes an existing LineCategory model.
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
     * Finds the LineCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LineCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LineCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCategoryWithoutLine()
    {
        $result = ['status' => 'error'];
        if (isset($_POST['shop_id']) && isset($_POST['line_category_id'])) {

            $categories_array = Category::withOutLine($_POST['shop_id'], $_POST['line_category_id']);
            $result = ['status' => 'success', 'categories' => $categories_array];
        }
        Yii::$app->response->format = 'json';
        return $result;
    }
}
