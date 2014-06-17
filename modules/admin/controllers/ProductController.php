<?php

namespace app\modules\admin\controllers;

use app\models\PhotoGallery;
use app\modules\admin\assets\AdminAsset;
use Yii;
use app\models\Product;
use app\models\search\ProductSearch;
use app\modules\admin\components\AdminController;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends AdminController
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product;
        $model->setScenario('admin');
        $model->prepare();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->shop_id = 1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('admin');
        $model->prepare();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPhotoGallery($id, $color_id = null)
    {
        /**
         * @TODO перенести в scope в PhotoGallery
         */
        $model = Product::find()
            ->with([
                'productColors.color',
                'photoGalleries' => function ($q) use ($color_id) {
                        $q->andWhere(['type' => PhotoGallery::TYPE_PRODUCT]);
                        $q->andWhere(['color_id' => $color_id]);
                        $q->orderBy(['sort' => SORT_ASC]);
                    },
            ])
            ->andWhere(['id' => $id])
            ->one();

        return $this->render('photo_gallery', [
            'model' => $model,
            'color_id' => $color_id,
        ]);
    }

    /**
     * @TODO перенести функции в другой контроллер, заменить product_id на object_id, добавить type
     * @return array
     */
    public function actionAddPhoto()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['product_id']) && isset($_POST['upload_tmp']) && isset($_POST['upload_name'])) {
            $color_id = isset($_POST['color_id']) ? $_POST['color_id'] : null;
            $pg = PhotoGallery::find()
                ->andWhere(['object_id' => $_POST['product_id']])
                ->andWhere(['type' => PhotoGallery::TYPE_PRODUCT])
                ->andWhere(['color_id' => $color_id])
                ->orderBy(['sort' => SORT_DESC])->one();
            $sort = is_null($pg) ? 1 : $pg->sort + 1;
            $photo_gallery = new PhotoGallery();
            $photo_gallery->object_id = $_POST['product_id'];
            $photo_gallery->type = PhotoGallery::TYPE_PRODUCT;
            $photo_gallery->sort = $sort;
            $photo_gallery->color_id = $color_id;
            $photo_gallery->upload_tmp = $_POST['upload_tmp'];
            $photo_gallery->upload_name = $_POST['upload_name'];
            $photo_gallery->save();

            $html = $photo_gallery->renderSortItem();
            return ['status' => 'success', 'img' => $html];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionDeletePhoto()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['photo_gallery_id'])) {
            $photo_gallery = PhotoGallery::findOne($_POST['photo_gallery_id']);
            $photo_gallery->delete();

            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionSavePhotoSort()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['sort'])) {
            $ids = array_keys($_POST['sort']);
            $photo_galleries = PhotoGallery::findAll(['id' => $ids]);
            foreach ($photo_galleries as $pg) {
                $pg->sort = $_POST['sort'][$pg->id];
                $pg->save();
            }
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }
}
