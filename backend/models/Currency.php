<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 *
 * @property Exchange[] $exchanges
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Exchanges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExchanges()
    {
        return $this->hasMany(Exchange::class, ['currency_id' => 'id']);
    }
}
