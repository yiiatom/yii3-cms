<?php

declare(strict_types=1);

namespace Atom\Cms\Service;

use Atom\Cms\Entity\User;
use Atom\Cms\Repository\UserRepository;
use Yiisoft\Security\PasswordHasher;
use Yiisoft\User\CurrentUser;

final readonly class UserService
{
    public function __construct(
        private CurrentUser $currentUser,
        private UserRepository $userRepository,
    ) {}

    public function login(string $username, string $password): bool
    {
        $identity = $this->userRepository->findOneByUsername($username);
        if (!$identity) {
            return false;
        }

        $hasher = new PasswordHasher();
        if (!$identity->password || !$hasher->validate($password, $identity->password)) {
            return false;
        }

        $this->currentUser->login($identity);

        return true;
    }

    public function logout(): bool
    {
        return $this->currentUser->logout();
    }
}
