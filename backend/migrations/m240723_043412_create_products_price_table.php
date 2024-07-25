<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products_price}}`.
 */
class m240723_043412_create_products_price_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('products_price', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'exchange_id' => $this->integer(),
            'price' => $this->float(),
        ]);

        $this->createIndex(
            'idx-products_price-product_id',
            'products_price',
            'product_id'
        );

        $this->addForeignKey(
            'fk-products_price-product_id',
            'products_price',
            'product_id',
            'products',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-products_price-exchange_id',
            'products_price',
            'exchange_id'
        );

        $this->addForeignKey(
            'fk-products_price-exchange_id',
            'products_price',
            'exchange_id',
            'exchange',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->insert('products_price', [
            'product_id' => 1,
            'price' => 10000,
            'exchange_id' => 1,
        ]);

        $this->insert('products_price', [
            'product_id' => 2,
            'price' => 5000,
            'exchange_id' => 1,
        ]);

        $this->insert('products_price', [
            'product_id' => 1,
            'price' => 113.92,
            'exchange_id' => 2,
        ]);

        $this->insert('products_price', [
            'product_id' => 2,
            'price' => 56.96,
            'exchange_id' => 2,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-products_price-product_id', 'products_price');
        $this->dropForeignKey('fk-products_price-exchange_id', 'products_price');
        $this->dropIndex('idx-products_price-product_id', 'products_price');
        $this->dropIndex('idx-products_price-exchange_id', 'products_price');

        $this->dropTable('products_price');
    }
}
