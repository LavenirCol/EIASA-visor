<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        's3' => [
            'class' => 'bpsys\yii2\aws\s3\Service',
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'region' => '',
            'defaultBucket' => '',
            'defaultAcl' => ''
        ]
    ],
];
