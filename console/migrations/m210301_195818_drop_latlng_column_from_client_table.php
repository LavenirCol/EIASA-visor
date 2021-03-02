<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%client}}`.
 */
class m210301_195818_drop_latlng_column_from_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%client}}', 'latlng');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%client}}', 'latlng', $this->string(250));
    }
}
