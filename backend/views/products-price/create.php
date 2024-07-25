<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductsPrice $model */

$this->title = 'Create Products Price';
$this->params['breadcrumbs'][] = ['label' => 'Products Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
