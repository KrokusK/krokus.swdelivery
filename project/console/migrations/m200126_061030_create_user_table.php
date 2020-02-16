<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200126_061030_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(0),
//            'role' => $this->smallInteger()->notNull()->defaultValue(0),
            'status_order' => $this->smallInteger()->notNull()->defaultValue(1),

            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
            'midname' => $this->string()->defaultValue(null),

            'limit' => $this->integer()->notNull(),

            'start_order_cancel' => $this->string()->defaultValue(null),
            'end_order_cancel' => $this->string()->defaultValue(null),

            'password_reset_token_created_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%elect}}', [
            'id_user' => $this->integer()->notNull(),
            'id_dish' => $this->integer()->notNull(),
            'PRIMARY KEY(id_user, id_dish)',
        ]);

        $this->createIndex('idx-elect-id_user', '{{%elect}}', 'id_user');
        $this->createIndex('idx-elect-id_dish', '{{%elect}}', 'id_dish');

        $this->addForeignKey('fk-elect-user', '{{%elect}}', 'id_user', '{{%user}}',
            'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-elect-dish', '{{%elect}}', 'id_dish', '{{%dish}}',
            'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%elect}}');
        $this->dropTable('{{%user}}');
    }
}
