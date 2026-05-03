<?php

declare(strict_types=1);

namespace Atom\User;

use DateTimeImmutable;
use Atom\User\UserRepository;
use Yiisoft\User\Event\AfterLogin;

final readonly class AfterLoginListener
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    function __invoke(
        AfterLogin $event,
    ): void {
        $identity = $event->getIdentity();
        $identity->loginAt = new DateTimeImmutable();
        $identity->loginIp = $_SERVER['REMOTE_ADDR'] ?? null;
        $this->userRepository->save($identity);
    }
}
