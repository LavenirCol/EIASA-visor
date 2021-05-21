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
                'key' => 'AKIA3U2TKEUPSRMPDX67',
                'secret' => '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',
            ],
            'region' => 'us-west-2',
            'defaultBucket' => 'umbrella2',
            'defaultAcl' => 'public-read'
        ]
    ],
];
