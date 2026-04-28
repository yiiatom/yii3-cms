<?php

declare(strict_types=1);

namespace Atom\Cms\Repository;

use Atom\Cms\Entity\User;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class UserRepository implements IdentityRepositoryInterface
{
    public function __construct(
        private ConnectionInterface $connection,
    ) { }

    public function findIdentity(string $id): ?IdentityInterface
    {
        return $this->findOne($id);
    }

    public function findIdentityByToken(string $token, string $type): ?IdentityInterface
    {
        return $this->findOneByToken($token);
    }

    private function createEntity(?array $row): ?User
    {
        if ($row === null) {
            return null;
        }

        return User::create(
            uuid: $row['uuid'],
            username: $row['username'],
            password: $row['password'],
            token: $row['token'],
        );
    }

    public function findOne(string $uuid): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('uuid = :uuid', ['uuid' => $uuid]);

        return $this->createEntity($query->one());
    }

    public function findOneByToken(string $token): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('token = :token', ['token' => $token]);

        return $this->createEntity($query->one());
    }

    public function findOneByUsername(string $username): ?User
    {
        $query = $this->connection
            ->select()
            ->from('{{%user}}')
            ->where('username = :username', ['username' => $username]);

        return $this->createEntity($query->one());
    }
}
