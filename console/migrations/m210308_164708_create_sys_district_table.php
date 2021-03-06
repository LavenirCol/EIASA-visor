<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sys_district}}`.
 */
class m210308_164708_create_sys_district_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_district}}', [
            'id' => $this->primaryKey().' NOT NULL AUTO_INCREMENT',
            'id_city' => $this->double(),
            'name' => $this->string(100),
            'status' => $this->integer(),
            'code' => $this->string(10),
            'iso' => $this->string(10),
        ]);
        $this->batchInsert('{{%sys_district}}',['id_city','name','status','code'],
            [[1, 'Leticia',1, '91001'],
            [1, 'Puerto Arica',1, '91536'],
            [2, 'Medellín',1, '05001'],
            [2, 'Sabaneta',1, '05631'],
            [2, 'Vigía del Fuerte',1, '05873'],
            [3, 'Cravo Norte',1, '81220'],
            [4, 'Galapa',1, '08296'],
            [5, 'Bogotá',1, '11001'],
            [6, 'San Jacinto',1, '13654'],
            [6, 'Turbaco',1, '13836'],
            [7, 'Tunja',1, '15001'],
            [7, 'Briceño',1, '15106'],
            [7, 'Páez',1, '15514'],
            [7, 'Tasco',1, '15790'],
            [7, 'Tutazá',1, '15839'],
            [8, 'Marmato',1, '17442'],
            [9, 'San José del Fragua',1, '18610'],
            [10, 'Monterrey',1, '85162'],
            [11, 'Popayán',1, '19001'],
            [11, 'Caloto',1, '19142'],
            [12, 'Valledupar',1, '20001'],
            [13, 'Quibdó',1, '27001'],
            [13, 'Acandí',1, '27006'],
            [14, 'Montería',1, '23001'],
            [14, 'San Antero',1, '23672'],
            [15, 'Fusagasugá',1, '25290'],
            [15, 'Chía',1, '25175'],
            [15, 'Quetame',1, '25594'],
            [15, 'Tibacuy',1, '25805'],
            [15, 'Topaipí',1, '25823'],
            [16, 'Inírida',1, '94001'],
            [16, 'La Guadalupe',1, '94885'],
            [17, 'San José del Guaviare',1, '95001'],
            [18, 'Campoalegre',1, '41132'],
            [18, 'Hobo',1, '41349'],
            [19, 'Riohacha',1, '44001'],
            [20, 'Santa Marta',1, '47001'],
            [20, 'San Zenón',1, '47703'],
            [21, 'Villavicencio',1, '50001'],
            [21, 'Barranca de Upía',1, '50110'],
            [22, 'Pasto',1, '52001'],
            [22, 'El Peñol',1, '52254'],
            [22, 'Linares',1, '52411'],
            [23, 'Cúcuta',1, '54001'],
            [23, 'Labateca',1, '54377'],
            [24, 'Puerto Asís',1, '86568'],
            [25, 'Pijao',1, '63548'],
            [26, 'Santa Rosa de Cabal',1, '66682'],
            [27, 'Providencia',1, '88564'],
            [28, 'Bucaramanga',1, '68001'],
            [28, 'Ocamonte',1, '68498'],
            [28, 'Zapatoca',1, '68895'],
            [29, 'Sincelejo',1, '70001'],
            [30, 'Honda',1, '73001'],
            [30, 'Armero',1, '73055'],
            [31, 'Versalles',1, '76863'],
            [31, 'Yumbo',1, '76892'],
            [32, 'Carurú',1, '97161'],
            [33, 'Puerto Carreño',1, '99001'],
            [2, 'Barbosa',1, '05079'],
            [2, 'Girardota',1, '05308'],
            [2, 'Copacabana',1, '05212'],
            [2, 'Itagui',1, '05360'],
            [2, 'Bello',1, '05088'],
            [2, 'Envigado',1, '05266'],
            [8, 'Manizales',1, '17001'],
            [15, 'La Calera',1, '25377'],
            [15, 'Cota',1, '25214'],
            [15, 'Cundinamarca',1, '25'],
            [22, 'Pasto',1, '52001'],
            [22, 'Pasto',1, '52001'],
            [25, 'Armenia',1, '63001'],
            [31, 'Guacarí',1, '76318'],
            [31, 'Ginebra',1, '76306'],
            [31, 'Riofrío',1, '76616'],
            [31, 'Yotoco',1, '76890'],
            [31, 'Palmira',1, '76520'],
            [31, 'Cali',1, '76001'],
            [31, 'Guadalajara De Buga',1, '76111'],
            [2, 'Abejorral',1, '05002'],
            [2, 'Amaga',1, '05030'],
            [2, 'Amalfi',1, '05031'],
            [2, 'Andes',1, '05034'],
            [2, 'Angelopolis',1, '05036'],
            [2, 'Santafé de antioquia',1, '05042'],
            [2, 'Apartado',1, '05045'],
            [2, 'Arboletes',1, '05051'],
            [2, 'Betulia',1, '05093'],
            [2, 'Ciudad bolívar',1, '05101'],
            [2, 'Cáceres',1, '05120'],
            [2, 'Carepa',1, '05147'],
            [2, 'El carmen de viboral',1, '05148'],
            [2, 'Caucasia',1, '05154'],
            [2, 'Chigorodo',1, '05172'],
            [2, 'Cisneros',1, '05190'],
            [2, 'Concordia',1, '05209'],
            [2, 'Donmatias',1, '05237'],
            [2, 'El bagre',1, '05250'],
            [2, 'Entrerrios',1, '05264'],
            [2, 'Fredonia',1, '05282'],
            [2, 'Gómez plata',1, '05310'],
            [2, 'Guarne',1, '05318'],
            [2, 'Ituango',1, '05361'],
            [2, 'Jericó',1, '05368'],
            [2, 'La pintada',1, '05390'],
            [2, 'La unión',1, '05400'],
            [2, 'Necocli',1, '05490'],
            [2, 'Nechi',1, '05495'],
            [2, 'Peñol',1, '05541'],
            [2, 'Puerto berrio',1, '05579'],
            [2, 'Remedios',1, '05604'],
            [2, 'San juan de urabá',1, '05659'],
            [2, 'San pedro de los milagros',1, '05664'],
            [2, 'San pedro de urabá',1, '05665'],
            [2, 'San roque',1, '05670'],
            [2, 'Santa bárbara',1, '05679'],
            [2, 'Santa rosa de osos',1, '05686'],
            [2, 'El santuario',1, '05697'],
            [2, 'Segovia',1, '05736'],
            [2, 'Sonsón',1, '05756'],
            [2, 'Sopetran',1, '05761'],
            [2, 'Taraza',1, '05790'],
            [2, 'Titiribí',1, '05809'],
            [2, 'Turbo',1, '05837'],
            [2, 'Urrao',1, '05847'],
            [2, 'Valdivia',1, '05854'],
            [2, 'Venecia',1, '05861'],
            [2, 'Yondo',1, '05893'],
            [2, 'Zaragoza',1, '05895'],
            [13, 'Certegui',1, '27160'],
            [13, 'Condoto',1, '27205'],
            [13, 'Medio san juan',1, '27450'],
            [13, 'Riosucio',1, '27615'],
            [13, 'Tado',1, '27787']]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sys_district}}');
    }
}
