<?php
declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\Collection\FcmTokenCollection;
use App\Domain\Collection\UserIdCollection;
use App\Domain\ValueObject\UserId;
use App\Models\FcmToken;
use Exception;

interface FcmTokenRepositoryInterface
{
    /**
     * @param \App\Domain\ValueObject\FcmToken $fcmToken
     * @return FcmToken
     * @throws Exception
     */
    public function findByToken(\App\Domain\ValueObject\FcmToken $fcmToken): FcmToken;

    /**
     * @param UserId $userId
     * @return FcmToken
     * @throws Exception
     */
    public function findByUserId(UserId $userId): FcmToken;

    /**
     * @param UserIdCollection $userIdCollection
     * @return FcmTokenCollection
     */
    public function findByUserIds(UserIdCollection $userIdCollection): FcmTokenCollection;

    /**
     * @param UserId $userId
     * @param \App\Domain\ValueObject\FcmToken $fcmToken
     * @return FcmToken
     * @throws Exception
     */
    public function create(UserId $userId, \App\Domain\ValueObject\FcmToken $fcmToken): FcmToken;

    /**
     * @param \App\Domain\ValueObject\FcmToken $fcmToken
     * @return bool
     * @throws Exception
     */
    public function delete(\App\Domain\ValueObject\FcmToken $fcmToken): bool;
}
