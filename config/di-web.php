<?php

declare(strict_types=1);

use Atom\Cms\Repository\UserRepository;
use Yiisoft\Auth\IdentityRepositoryInterface;


return [
    IdentityRepositoryInterface::class => UserRepository::class,
];
