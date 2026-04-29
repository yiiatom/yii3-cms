<?php

declare(strict_types=1);

namespace Atom\Cms\ViewInjection;

use Atom\Cms\Service\UserService;
use Yiisoft\Yii\View\Renderer\LayoutParametersInjectionInterface;

final readonly class UserServiceInjection implements LayoutParametersInjectionInterface
{
    public function __construct(
        private UserService $userService
    ) {}

    public function getLayoutParameters(): array
    {
        return [
            'userService' => $this->userService,
        ];
    }
}
