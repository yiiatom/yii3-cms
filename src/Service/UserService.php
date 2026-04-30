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

    public function validatePassword(User $identity, string $password): bool
    {
        if ($identity->password === null) {
            return false;
        }

        return (new PasswordHasher())->validate($password, $identity->password);
    }

    public function changePassword(User $identity, string $password): void
    {
        $identity->password = (new PasswordHasher())->hash($password);
        $this->userRepository->save($identity);
    }

    public function getDisplayName(?User $identity = null): string
    {
        if ($identity === null) {
            if ($this->currentUser->isGuest()) {
                return 'Guest';
            } else {
                $identity = $this->currentUser->getIdentity();
            }
        }

        $firstName = $identity->firstName ?? "";
        $lastName = $identity->lastName ?? "";

        $displayName = trim($firstName . ' ' . $lastName);

        if (!$displayName) {
            $displayName = $identity->username;
        }

        return $displayName;
    }

    public function getAvatarUrl(?User $identity = null): ?string
    {
        if ($identity === null) {
            if ($this->currentUser->isGuest()) {
                return null;
            } else {
                $identity = $this->currentUser->getIdentity();
            }
        }

        return $identity->avatarUrl;
    }
}
