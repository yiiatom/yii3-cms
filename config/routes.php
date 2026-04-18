<?php

declare(strict_types=1);

use Atom\Cms;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Group::create('/cms')
        ->routes(
            Route::get('/')
                ->action(Cms\Dashboard\Action::class)
                ->name('cms.dashboard'),
        ),
];
