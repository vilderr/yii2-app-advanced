<?php
return [
    'bootstrap'  => [
        'queue',
    ],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'       => [
            'class' => 'yii\redis\Cache',
        ],
        'authManager' => [
            'class'           => 'yii\rbac\DbManager',
            'itemTable'       => '{{%auth_items}}',
            'itemChildTable'  => '{{%auth_item_children}}',
            'assignmentTable' => '{{%auth_assignments}}',
            'ruleTable'       => '{{%auth_rules}}',
        ],
        'queue'       => [
            'class' => 'yii\queue\redis\Queue',
        ],
    ],
];
