<?php

declare(strict_types=1);

namespace Atom\Cms\Entity;

use Atom\Cms\Repository\UserRepository;
use Yiisoft\Auth\IdentityInterface;

final readonly class User implements IdentityInterface
{
    private function __construct(
        public string $uuid,
        public string $username,
        public ?string $password,
        public ?string $token,
    ) {}

    public static function create(
        string $uuid,
        string $username,
        ?string $password,
        ?string $token,
    ): self
    {
        return new self(
            uuid: $uuid,
            username: $username,
            password: $password,
            token: $token,
        );
    }

    public function getId(): string
    {
        return $this->uuid;
    }
}
