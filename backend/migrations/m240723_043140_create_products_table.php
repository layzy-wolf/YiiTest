<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240723_043140_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'description' => $this->text()->null()
        ]);

        $this->insert('products', [
            'name' => 'билет на тренинг',
        ]);

        $this->insert('products', [
            'name' => 'индивидуальная консультация',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('products');
    }
}
