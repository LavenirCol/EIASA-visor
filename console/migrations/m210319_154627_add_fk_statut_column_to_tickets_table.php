<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tickets}}`.
 */
class m210319_154627_add_fk_statut_column_to_tickets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tickets}}', 'fk_statut', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tickets}}', 'fk_statut');
    }
}
