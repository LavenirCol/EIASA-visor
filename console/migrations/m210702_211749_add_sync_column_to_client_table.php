<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%client}}`.
 */
class m210702_211749_add_sync_column_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client}}', 'sync', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client}}', 'sync');
    }
}
