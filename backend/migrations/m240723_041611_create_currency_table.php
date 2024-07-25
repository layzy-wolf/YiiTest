<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m240723_041611_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'code' => $this->string(255),
            'name' => $this->string(255)
        ]);

        $this->insert('currency', [
            'code' => 'RUB',
            'name' => 'Рубли',
        ]);

        $this->insert('currency', [
            'code' => 'USD',
            'name' => 'Доллары США',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('currency');
    }
}
