<?php
declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\ValueObject\UserEmail;
use App\User;

interface UserRepositoryInterface
{
    /**
     * @param UserEmail $email
     * @return User
     */
    public function findByEmail(UserEmail $email): User;
}
