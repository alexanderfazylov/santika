<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use app\models\Collection;
use app\models\Line;
use app\models\LineProduct;
use app\models\Product;
use app\models\Shop;
use app\models\Upload;
use app\modules\admin\components\AdminController;
use Yii;
use app\models\ShowWith;
use app\models\search\ShowWithSearch;
use yii\base\Model;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShowWithController implements the CRUD actions for ShowWith model.
 */
class ShowWithController extends AdminController
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
     * Lists all ShowWith models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShowWithSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single ShowWith model.
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
     * Creates a new ShowWith model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShowWith;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShowWith model.
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
     * Deletes an existing ShowWith model.
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
     * Finds the ShowWith model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShowWith the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShowWith::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProduct()
    {
        $shop_id = Shop::getIdFromUrl();
        $products = Product::find()->byShop($shop_id)->all();
        $model = new ShowWith();
        return $this->render('product', [
            'shop_id' => $shop_id,
            'products' => $products,
            'model' => $model,
        ]);
    }

    public function actionGetProducts()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['object_id']) && isset($_POST['type'])) {
            $object_id = $_POST['object_id'];
            $type = $_POST['type'];
            $show_withs = ShowWith::find()
                ->with('product.photo')
                ->andWhere(['type' => $type])
                ->andWhere(['object_id' => $object_id])
                ->orderBy(['sort' => SORT_ASC])
                ->all();
            $result = [];

            foreach ($show_withs as $show_with) {
                $product = $show_with->product;
                $result[] = [
                    'id' => $show_with->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'photo' => !empty($product->photo_id) ? $product->photo->getFileShowUrl() : Upload::defaultFileUrl(),
                    'type' => $type,
                    'object_id' => $object_id,
                ];
            }
            return ['status' => 'success', 'products' => $result];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }


    public function actionGetObjects()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['shop_id']) && isset($_POST['type'])) {
            $shop_id = $_POST['shop_id'];
            $query = null;

            switch ($_POST['type']) {
                case ShowWith::TYPE_LINE:
                    $query = Line::find();
                    break;
                case ShowWith::TYPE_COLLECTION:
                    $query = Collection::find();
                    break;
                case ShowWith::TYPE_CATEGORY:
                    $query = Category::find();
                    break;
                case ShowWith::TYPE_PRODUCT:
                    $query = Product::find();
                    break;
                default:
                    break;

            }
            if (is_null($query)) {
                $objects = [];
            } else {
                $objects = $query->byShop($shop_id)->asArray()->all();
            }
            return ['status' => 'success', 'objects' => $objects];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionAddProduct()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['object_id']) && isset($_POST['product_id']) && isset($_POST['type'])) {
            $sw = ShowWith::find()
                ->andWhere(['object_id' => $_POST['object_id']])
                ->andWhere(['type' => $_POST['type']])
                ->andWhere(['product_id' => $_POST['product_id']])
                ->orderBy(['sort' => SORT_DESC])
                ->one();

            $sort = 1;
            if (!is_null($sw)) {
                $sort = ++$sw->sort;
            }

            $show_with = new ShowWith();
            $show_with->object_id = $_POST['object_id'];
            $show_with->type = $_POST['type'];
            $show_with->product_id = $_POST['product_id'];
            $show_with->sort = $sort;

            if ($show_with->save()) {
                $product = Product::findOne($show_with->product_id);
                $result = [
                    'id' => $show_with->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'photo' => !empty($product->photo_id) ? $product->photo->getFileShowUrl() : Upload::defaultFileUrl(),
                    'type' => $show_with->type,
                    'object_id' => $show_with->object_id,
                ];
                return ['status' => 'success', 'product' => $result];
            } else {
                return ['status' => 'error', 'messages' => $show_with->getErrors()];
            }
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionDeleteProduct()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['id'])) {
            $show_with = ShowWith::findOne($_POST['id']);

            if ($show_with->delete()) {
                return ['status' => 'success'];
            } else {
                return ['status' => 'error', 'messages' => $show_with->getErrors()];
            }
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }

    public function actionSaveSort()
    {
        Yii::$app->response->format = 'json';
        if (isset($_POST['sort'])) {
            $ids = array_keys($_POST['sort']);
            $show_withs = ShowWith::findAll(['id' => $ids]);
            foreach ($show_withs as $show_with) {
                $show_with->sort = $_POST['sort'][$show_with->id];
                $show_with->save();
            }
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Не корректный запрос'];
    }
}
