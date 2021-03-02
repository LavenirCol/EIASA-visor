<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%client}}`.
 */
class m210301_195937_add_lat_column_to_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client}}', 'lng', $this->string(45)->after('lat'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client}}', 'lng');
    }
}
