<?php
declare(strict_types=1);

namespace App\Domain\Repository;


use App\Domain\Collection\FcmTokenCollection;
use App\Domain\Collection\UserIdCollection;
use App\Domain\ValueObject\UserId;
use App\Models\FcmToken;

final class FcmTokenRepository implements FcmTokenRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findByToken(\App\Domain\ValueObject\FcmToken $fcmToken): FcmToken
    {
        $token = FcmToken::where('token', (string) $fcmToken)->first();
        if (!$token instanceof FcmToken) {
            throw new \Exception('このトークンは存在しません。');
        }
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function findByUserId(UserId $userId): FcmToken
    {
        $token = FcmToken::where('user_id', $userId->toInt())->first();
        if (!$token instanceof FcmToken) {
            throw new \Exception('このトークンは存在しません。');
        }
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function findByUserIds(UserIdCollection $userIdCollection): FcmTokenCollection
    {
        $tokens = FcmToken::whereIn('user_id', $userIdCollection->toArray())->get('token');
        $fcmTokenCollection = new FcmTokenCollection();
        foreach ($tokens as $token) {
            $fcmTokenCollection->push($token->token);
        }
        return $fcmTokenCollection;
    }

    /**
     * @inheritDoc
     */
    public function create(UserId $userId, \App\Domain\ValueObject\FcmToken $fcmToken): FcmToken
    {
        $result = FcmToken::firstOrNew([
            'token' => (string) $fcmToken,
        ])->fill([
            'user_id' => $userId->toInt(),
        ])->save();
        if ($result === false) {
            throw new \Exception('トークンの登録に失敗しました。');
        }
        return $this->findByToken($fcmToken);
    }

    /**
     * @inheritDoc
     */
    public function delete(\App\Domain\ValueObject\FcmToken $fcmToken): bool
    {
        $token = $this->findByToken($fcmToken);
        $result = $token->delete();
        if ($result === null) {
            return true;
        }
        return $result;
    }
}
