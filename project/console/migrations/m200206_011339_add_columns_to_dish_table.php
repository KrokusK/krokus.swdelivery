<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%dish}}`.
 */
class m200206_011339_add_columns_to_dish_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dish', 'weight', $this->string()->notNull());
        $this->addColumn('dish', 'weighty', $this->smallInteger()->notNull());
        $this->addColumn('dish', 'single', $this->smallInteger()->notNull());
        $this->addColumn('dish', 'description', $this->string());
        $this->addColumn('dish', 'image', $this->string());
        $this->addColumn('dish', 'isGarnish', $this->smallInteger()->notNull());
        $this->addColumn('dish', 'maybeGarnish', $this->smallInteger()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
