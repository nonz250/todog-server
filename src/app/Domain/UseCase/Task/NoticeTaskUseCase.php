<?php
declare(strict_types=1);

namespace App\Domain\UseCase\Task;


use App\Domain\Collection\FcmTokenCollection;
use App\Domain\Collection\UserIdCollection;
use App\Domain\Repository\FcmTokenRepository;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\UseCase\FirebaseCloudMessaging\SendFcmNotificationUseCase;
use App\Domain\ValueObject\FcmBody;
use App\Domain\ValueObject\FcmClickAction;
use App\Domain\ValueObject\FcmIcon;
use App\Domain\ValueObject\FcmNotification;
use App\Domain\ValueObject\FcmTimeToLeave;
use App\Domain\ValueObject\FcmTitle;
use App\Domain\ValueObject\FcmToken;
use App\Domain\ValueObject\UserId;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class NoticeTaskUseCase
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * @var FcmTokenRepository
     */
    private $fcmTokenRepository;

    /**
     * NoticeTaskUseCase constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     * @param FcmTokenRepository $fcmTokenRepository
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        FcmTokenRepository $fcmTokenRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->fcmTokenRepository = $fcmTokenRepository;
    }

    public function __invoke()
    {
        $userIds = Arr::pluck(User::all()->toArray(), 'id');
        $userIdCollection = new UserIdCollection();

        foreach ($userIds as $userId) {
            $userIdCollection->push(new UserId((int) $userId));
        }

        $limitedTasks = $this->taskRepository->findByUserIdsAndLimitDateWithFcmToken($userIdCollection, Carbon::now());

        $result = DB::transaction(function () use ($limitedTasks) {
            $fcmTokenCollection = new FcmTokenCollection();

            /** @var Model $limitedTask */
            foreach ($limitedTasks as $limitedTask) {
                /** @var \App\Models\FcmToken $token */
                foreach ($limitedTask->token as $token) {
                    try {
                        $fcmNotification = new FcmNotification(
                            new FcmToken((string) $token->getAttribute('token')),
                            new FcmTitle('期限が近いタスクがあります'),
                            new FcmBody((string) $limitedTask->getAttribute('name')),
                            new FcmIcon('/todog-round-icon.png'),
                            new FcmTimeToLeave(60),
                            new FcmClickAction(route('root'))
                        );
                        $sendFcmNotificationUseCase = new SendFcmNotificationUseCase($fcmNotification);
                        $sendFcmNotificationUseCase();
                    } catch (\Exception $e) {
                        Log::info($e->getMessage());
                        $fcmToken = new FcmToken((string) $token->getAttribute('token'));
                        if (!$fcmTokenCollection->existItem($fcmToken)) {
                            $fcmTokenCollection->push($fcmToken);
                        }
                        continue;
                    }
                }
            }

            /** @var FcmToken $fcmToken */
            foreach ($fcmTokenCollection as $fcmToken) {
                $this->fcmTokenRepository->delete($fcmToken);
            }

            return true;
        });
    }
}
