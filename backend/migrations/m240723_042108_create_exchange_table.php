<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%exchange}}`.
 */
class m240723_042108_create_exchange_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('exchange', [
            'id' => $this->primaryKey(),
            'currency_id' => $this->integer(),
            'rate' => $this->float(),
        ]);

        $this->createIndex(
            'idx-exchange-currency_id',
            'exchange',
            'currency_id'
        );

        $this->addForeignKey(
            'fk-exchange-currency_id',
            'exchange',
            'currency_id',
            'currency',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->insert('exchange', [
            'currency_id' => 1,
            'rate' => 1,
        ]);

        $this->insert('exchange', [
            'currency_id' => 2,
            'rate' => 0.011392,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-exchange-currency_id',
            'exchange'
        );

        $this->dropIndex(
            'idx-exchange-currency_id',
            'exchange'
        );

        $this->dropTable('exchange');
    }
}
