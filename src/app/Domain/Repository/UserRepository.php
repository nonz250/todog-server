<?php
declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\ValueObject\UserEmail;
use App\User;

final class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private $model;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(UserEmail $email): User
    {
        $user = $this->model
            ->newQuery()
            ->where('email', $email)
            ->first();

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('ユーザーを取得できませんでした。');
        }

        return $user;
    }
}
