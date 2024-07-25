<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase}}`.
 */
class m240723_044909_create_purchase_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('purchase', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'name' => $this->string(255),
            'telephone' => $this->string(255),
            'payment' => $this->boolean(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->createIndex(
            'idx-purchase-product_id',
            'purchase',
            'product_id'
        );

        $this->addForeignKey(
            'fk-purchase-product_id',
            'purchase',
            'product_id',
            'products_price',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-purchase-product_id', 'purchase');
        $this->dropIndex('idx-purchase-product_id', 'purchase');

        $this->dropTable('purchase');
    }
}
