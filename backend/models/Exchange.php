<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exchange".
 *
 * @property int $id
 * @property int|null $currency_id
 * @property float|null $rate
 *
 * @property Currency $currency
 * @property ProductsPrice[] $productsPrices
 */
class Exchange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exchange';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['currency_id'], 'integer'],
            [['rate'], 'number'],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::class, 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'currency_id' => 'Currency ID',
            'rate' => 'Rate',
        ];
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::class, ['id' => 'currency_id']);
    }

    /**
     * Gets query for [[ProductsPrices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductsPrices()
    {
        return $this->hasMany(ProductsPrice::class, ['exchange_id' => 'id']);
    }
}
