<?php

namespace app\controllers;

use app\models\Products;
use app\models\ProductsPrice;
use app\models\Purchase;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\rest\Controller;

class ApiController extends Controller
{

    public function actionProducts()
    {
        $res = [];
        $products = Products::find()->all();
        foreach ($products as $product) {
            /** @var $product Products */
            $res[$product->id]["name"] = $product->name;
            $res[$product->id]["description"] = $product->description ?? "";
            foreach ($product->productsPrices as $price) {
                if ($price->exchange->currency_id === 1) {
                    $res[$product->id]["price"] = $price->price;
                }
                if ($price->exchange->currency_id === 2) {
                    $res[$product->id]["currency"] = $price->price;
                    $res[$product->id]["currency_id"] = $price->id;
                }
            }
        }

        return $res;
    }

    public function actionPurchase()
    {
        $data = $this->request->post();
        $purchase = new Purchase();

        if (isset($data['product_id']) && isset($data['name']) && isset($data['telephone'])) {
            $purchase->product_id = $data['product_id'];
            $purchase->name = $data['name'];
            $purchase->telephone = $data['telephone'];
            $purchase->payment = isset($data['payment']) ? (int) $data['payment'] : 0;
        }

        $purchase->save();
        $purchase->created_at = 'now';
        return $purchase;
    }

    public function actionPurchaseList()
    {
        $filter = isset($this->request->post()["filter"]) ? $this->request->post()["filter"] : false;
        $order = isset($this->request->post()["order"]) ? $this->request->post()["order"] : false;
        $purchase = Purchase::find();

        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($key === "date") {
                    if ($value === "day") {
                        $purchase->where("created_at > CURRENT_DATE-1");
                    } else {
                        $purchase->where("created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)");
                    }
                    continue;
                }

                if ($key === "currency") {
                    if ($value) {
                        $purchase->join("JOIN", "products_price", "products_price.id = purchase.product_id");
                        $purchase->join("JOIN", "exchange", "products_price.exchange_id = exchange.id")
                            ->where("exchange.currency_id = 2");
                    }
                    continue;
                }

                $purchase->where("$key = $value");
            }
        }

        if ($order && $order === "old") {
            $purchase->orderBy("created_at DESC");
        }

        $purchase = $purchase->all();

        foreach ($purchase as $value) {
            /** @var $value Purchase */
            $value->product_id = Products::find()->where("id = $value->product_id")->one();
        }

        return $purchase;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['POST', 'PUT', 'OPTIONS', 'GET'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Headers' => ['Content-Type'],
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['*'],
            ],
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'products' => ['GET', 'HEAD', 'OPTIONS'],
            'purchase' => ['POST', 'HEAD', 'OPTIONS'],
            'purchase-list' => ['POST', 'HEAD', 'OPTIONS']
        ];
    }
}