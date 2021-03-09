<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sys_city}}`.
 */
class m210308_163927_create_sys_city_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_city}}', [
            'id' => $this->primaryKey().' NOT NULL AUTO_INCREMENT',
            'id_country' => $this->double(),
            'id_region' => $this->double(),
            'name' => $this->string(100),
            'status' => $this->integer(),
            'code' => $this->string(10),
            'iso' => $this->string(10),
        ]);

        $this->batchInsert('{{%sys_city}}',['id_country','id_region','name','status','code'],
        [
            [1, 3, 'Amazonas', 1, '91'],
            [1, 2, 'Antioquia', 1, '05'],
            [1, 2, 'Arauca', 1, '81'],
            [1, 1, 'Atlántico', 1, '08'],
            [1, 2, 'Bogotá', 1, '11'],
            [1, 1, 'Bolívar', 1, '13'],
            [1, 2, 'Boyacá', 1, '15'],
            [1, 2, 'Caldas', 1, '17'],
            [1, 3, 'Caquetá', 1, '18'],
            [1, 2, 'Casanare', 1, '85'],
            [1, 3, 'Cauca', 1, '19'],
            [1, 1, 'Cesar', 1, '20'],
            [1, 2, 'Chocó', 1, '27'],
            [1, 1, 'Córdoba', 1, '23'],
            [1, 2, 'Cundinamarca', 1, '25'],
            [1, 2, 'Guainía', 1, '94'],
            [1, 3, 'Guaviare', 1, '95'],
            [1, 3, 'Huila', 1, '41'],
            [1, 1, 'La Guajira', 1, '44'],
            [1, 1, 'Magdalena', 1, '47'],
            [1, 3, 'Meta', 1, '50'],
            [1, 3, 'Nariño', 1, '52'],
            [1, 1, 'Norte de Santander', 1, '54'],
            [1, 3, 'Putumayo', 1, '86'],
            [1, 2, 'Quindío', 1, '63'],
            [1, 2, 'Risaralda', 1, '66'],
            [1, 1, 'San Andrés y Providencia', 1, '88'],
            [1, 1, 'Santander', 1, '68'],
            [1, 1, 'Sucre', 1, '70'],
            [1, 3, 'Tolima', 1, '73'],
            [1, 3, 'Valle del Cauca', 1, '76'],
            [1, 3, 'Vaupés', 1, '97'],
            [1, 2, 'Vichada', 1, '99']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sys_city}}');
    }
}
