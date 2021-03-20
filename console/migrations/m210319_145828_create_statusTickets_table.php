<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statusTickets}}`.
 */
class m210319_145828_create_statusTickets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status_ticket}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'status_code' => $this->integer()->notNull()
        ]);

        $this->batchInsert('{{%status_ticket}}',['name','status_code'],
        [
            ['No leído',0],
            ['Leído', 1],
            ['Asignado', 2],
            ['En progreso', 3],
            ['Se necesita mas información', 5],
            ['En espera', 7],
            ['Cerrado', 8],
            ['Cancelado', 9]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%statusTickets}}');
    }
}
