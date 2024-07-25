<?php

namespace app\controllers;

use app\models\Currency;
use app\models\Exchange;
use app\models\ProductsPrice;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsPriceController implements the CRUD actions for ProductsPrice model.
 */
class ProductsPriceController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ProductsPrice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProductsPrice::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductsPrice model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProductsPrice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProductsPrice();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $price = $model->price;
            $exchangeId = $model->exchange_id;
            if ($exchangeId !== null) {
                $exchange = Exchange::findOne($exchangeId);
                if (empty($exchange)) {
                    return $this->redirect(['exchange/create']);
                } else {
                    $currencies = Currency::find()->all();
                    if ($exchange->currency_id !== 1) {
                        $model->exchange_id = Exchange::find()->where("currency_id = 1 ORDER BY id DESC")->one()->id;
                        $model->price = $price / $exchange->rate;
                    }
                    foreach ($currencies as $currency) {
                        if ($currency->id !== 1) {
                            $timedModel = new ProductsPrice();
                            $timedModel->product_id = $model->product_id;
                            $timedExchange = Exchange::find()->where("currency_id = $currency->id ORDER BY id DESC")->one();
                            $timedModel->exchange_id = $timedExchange->id;
                            if ($timedExchange->id === $exchangeId) {
                                $timedModel = $price;
                            } else {
                                $timedModel->price = $model->price * $timedExchange->rate;
                            }
                            $timedModel->save();
                        }
                    }
                }
            }
            try {
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $e) {
                return $this->redirect('create');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductsPrice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $products = ProductsPrice::find()->where("product_id = $model->product_id")->all();

            $rubProduct = new ProductsPrice();

            foreach ($products as $product) {
                if ($product->exchange->currency_id === 1) {
                    $rubProduct = $product;
                }
            }

            $rubProduct->price = $model->price / $model->exchange->rate;

            $rubProduct->save();

            foreach ($products as $product) {
                if ($product->id !== $rubProduct->id) {
                    $e = $product->exchange->currency_id;
                    $exchange = Exchange::find()->where("currency_id = $e ORDER bY id DESC")->one();
                    $product->exchange_id = $exchange->id;
                    $product->price = $rubProduct->price * $exchange->rate;
                    $product->save();
                }
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProductsPrice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductsPrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProductsPrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductsPrice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
