<?php

declare(strict_types=1);

use Atom\Cms\Web\Shared\Middleware\Authentication;
use Atom\Cms\Web\Shared\Middleware\LoginTheme;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Group::create('/cms')
        ->routes(
            Route::methods([Method::GET, Method::POST], '/login')
                ->middleware(LoginTheme::class)
                ->action(Atom\Cms\Web\Login\Action::class)
                ->name('atom.cms.login'),

            Group::create('')
                ->middleware(Authentication::class)
                ->routes(
                    Route::get('')
                        ->action(Atom\Cms\Web\Dashboard\Action::class)
                        ->name('atom.cms.dashboard'),

                    Route::get('/logout')
                        ->action(Atom\Cms\Web\Logout\Action::class)
                        ->name('atom.cms.logout'),
                ),
        ),
];
