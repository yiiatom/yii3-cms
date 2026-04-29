<?php

declare(strict_types=1);

use Atom\Cms\ViewInjection\UserServiceInjection;
use Yiisoft\Definitions\Reference;

return [
    'yiisoft/aliases' => [
        'aliases' => [
            '@atom/cms' => dirname(__DIR__),
        ],
    ],

    'yiisoft/yii-view-renderer' => [
        'injections' => [
            Reference::to(UserServiceInjection::class),
        ],
    ],

    'yiisoft/db-migration' => [
        'sourcePaths' => [
            dirname(__DIR__) . '/migrations',
            dirname(__DIR__) . '/../../yiisoft/rbac-db/migrations/items',
            dirname(__DIR__) . '/../../yiisoft/rbac-db/migrations/assignments',
        ]
    ],
];
