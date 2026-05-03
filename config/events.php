<?php

declare(strict_types=1);

use Atom\User\AfterLoginListener;
use Yiisoft\User\Event\AfterLogin;

return [
    AfterLogin::class => [
        [AfterLoginListener::class, '__invoke'],
    ],
];
