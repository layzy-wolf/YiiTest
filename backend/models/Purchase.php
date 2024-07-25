<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "purchase".
 *
 * @property int $id
 * @property int|null $product_id
 * @property string|null $name
 * @property string|null $telephone
 * @property int|null $payment
 *
 * @property Products $product
 */
class Purchase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'payment', 'created_at', 'updated_at'], 'integer'],
            [['name', 'telephone'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductsPrice::class, 'targetAttribute' => ['product_id' => 'id']],
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
            'name' => 'Name',
            'telephone' => 'Telephone',
            'payment' => 'Payment',
            'created_at' => 'CreatedAt',
            'updated_at' => 'UpdatedAt',
        ];
    }

    public function behaviors()
    {
        return [
        'timestamp' => [
            'class' => TimestampBehavior::className(),
            'attributes' => [
                BaseActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at'
            ],
            'value' => new Expression('NOW()')
        ],
    ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductsPrice::class, ['id' => 'product_id']);
    }
}
