<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%basket}}`.
 */
class m200126_061040_create_basket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%basket}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'date' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);

        $this->createTable('{{%basket_dish}}', [
            'id_basket' => $this->integer()->notNull(),
            'id_dish' =>$this->integer()->notNull(),
            'price' => $this->integer()->notNull(),
            'amount' => $this->integer()->defaultValue(1)->notNull(),
            'PRIMARY KEY(id_basket, id_dish)',
        ]);

        $this->createIndex('idx-basket_dish-id_dish', '{{%basket_dish}}', 'id_dish');
        $this->createIndex('idx-basket_dish-id_basket', '{{%basket_dish}}', 'id_basket');
        $this->createIndex('idx-basket-id_user', '{{%basket}}', 'id_user');

        $this->addForeignKey('fk-basket-user', '{{%basket}}', 'id_user', '{{%user}}',
            'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-basket_dish-basket', '{{%basket_dish}}', 'id_basket', '{{%basket}}',
            'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-basket_dish-dish', '{{%basket_dish}}', 'id_dish', '{{%dish}}',
            'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%basket_dish}}');
        $this->dropTable('{{%basket}}');
    }
}
