<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dish}}`.
 */
class m200125_171542_create_dish_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dish}}', [
            'id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'PRIMARY KEY(id)'
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dish}}');
    }
}
