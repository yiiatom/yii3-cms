<?php

declare(strict_types=1);

namespace Atom\Cms\Entity;

use DateTimeImmutable;
use Atom\Cms\Repository\UserRepository;
use Yiisoft\Auth\IdentityInterface;

final class User implements IdentityInterface
{
    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_ARCHIVED = 3;

    private function __construct(
        public string $uuid,
        public string $username,
        public ?string $email,
        public ?string $password,
        public ?string $token,
        public int $status,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $avatarUrl,
        public ?DateTimeImmutable $createdAt,
        public ?DateTimeImmutable $loginAt,
        public ?string $loginIp,
    ) {}

    public static function create(
        string $uuid,
        string $username,
        ?string $email = null,
        ?string $password = null,
        ?string $token = null,
        int $status = self::STATUS_PENDING,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $avatarUrl = null,
        ?DateTimeImmutable $createdAt = new DateTimeImmutable(),
        ?DateTimeImmutable $loginAt = null,
        ?string $loginIp = null,
    ): self
    {
        return new self(
            uuid: $uuid,
            username: $username,
            email: $email,
            password: $password,
            token: $token,
            status: $status,
            firstName: $firstName,
            lastName: $lastName,
            avatarUrl: $avatarUrl,
            createdAt: $createdAt,
            loginAt: $loginAt,
            loginIp: $loginIp,
        );
    }

    public function getId(): string
    {
        return $this->uuid;
    }
}
