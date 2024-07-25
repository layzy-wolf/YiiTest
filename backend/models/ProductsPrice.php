<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products_price".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $exchange_id
 * @property float|null $price
 *
 * @property Exchange $exchange
 * @property Products $product
 */
class ProductsPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'exchange_id'], 'integer'],
            [['price'], 'number'],
            [['exchange_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::class, 'targetAttribute' => ['exchange_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'exchange_id' => 'Exchange ID',
            'price' => 'Price',
        ];
    }

    /**
     * Gets query for [[Exchange]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExchange()
    {
        return $this->hasOne(Exchange::class, ['id' => 'exchange_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }
}
