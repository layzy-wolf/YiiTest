<?php

namespace app\commands;

use app\models\Currency;
use app\models\Exchange;
use app\models\ProductsPrice;
use app\models\Purchase;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;
use yii\helpers\Console;

class ConsoleController extends Controller
{
    public function actionIndex()
    {
        echo "console part \n";
        return ExitCode::OK;
    }

    public function actionCurrency($new)
    {
        /** @var $exchange Exchange */
        $exchange = Exchange::find()->where("currency_id = 2")->one();
        $exchange->rate = $new;
        if ($exchange->save()) {
            echo "success \n";
            return ExitCode::OK;
        };

        return ExitCode::UNSPECIFIED_ERROR;
    }

    public function actionPrice($item, $new)
    {
        $model = ProductsPrice::findOne($item);
        $model->price = $new;

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
            echo "success \n";
            return ExitCode::OK;
        }

        return ExitCode::UNSPECIFIED_ERROR;

    }

    public
    function actionPurchase($product_id, $name, $telephone, $payment = false)
    {
        $purchase = new Purchase();

        if (isset($product_id) && isset($name) && isset($telephone)) {
            $purchase->product_id = $product_id;
            $purchase->name = $name;
            $purchase->telephone = $telephone;
            $purchase->payment = $payment ? 1 : 0;
            if ($purchase->save()) {
                echo "success \n";
                return ExitCode::OK;
            }
        }
        $this->stdout("error \n", BaseConsole::FG_RED);
        return ExitCode::UNSPECIFIED_ERROR;
    }
}