<?php
declare(strict_types=1);

namespace App\Domain\UseCase\FirebaseCloudMessaging;


use App\Domain\ValueObject\FcmNotification;
use App\Services\Send\Fcm\FcmSendService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class SendFcmNotificationUseCase
{
    /**
     * @var FcmNotification
     */
    private $notification;

    /**
     * SendFcmNotificationUseCase constructor.
     *
     * @param FcmNotification $notification
     */
    public function __construct(FcmNotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return stdClass
     * @throws GuzzleException
     */
    public function __invoke(): stdClass
    {
        $send = new FcmSendService($this->notification);
        $contents = $send
            ->post()
            ->key((string) config('todog.fcm.key'))
            ->notification()
            ->send();
        if ($contents->success !== 1) {
            throw new Exception('テスト通知に失敗しました。');
        }
        return $contents;
    }
}
