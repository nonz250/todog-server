<?php
declare(strict_types=1);

namespace App\Services\Send\Fcm;


use App\Domain\ValueObject\FcmBody;
use App\Domain\ValueObject\FcmClickAction;
use App\Domain\ValueObject\FcmIcon;
use App\Domain\ValueObject\FcmNotification;
use App\Domain\ValueObject\FcmTimeToLeave;
use App\Domain\ValueObject\FcmTitle;
use App\Domain\ValueObject\FcmToken;
use App\Services\Send\SendService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psy\Util\Json;
use stdClass;

class FcmSendService extends SendService implements FcmSendInterface
{
    /** @var string */
    protected const SEND_URL = 'https://fcm.googleapis.com/fcm/send';
    /** @var string */
    protected const MODE_NOTIFICATION = 'notification';
    /** @var string */
    protected const MODE_DATA = 'data';

    /** @var FcmToken */
    protected $token;
    /** @var FcmTitle */
    protected $title;
    /** @var FcmBody */
    protected $body;
    /** @var FcmIcon */
    protected $icon;
    /** @var FcmTimeToLeave */
    protected $timeToLeave;
    /** @var FcmClickAction */
    protected $clickAction;
    /** @var string */
    protected $mode = self::MODE_NOTIFICATION;

    /**
     * FcmSendService constructor.
     *
     * @param FcmNotification $notification
     */
    public function __construct(FcmNotification $notification)
    {
        parent::__construct();
        $this
            ->url(self::SEND_URL)
            ->post()
            ->key((string) config('todog.fcm.key'))
            ->notification()
            ->to($notification->getToken())
            ->title($notification->getTitle())
            ->body($notification->getBody())
            ->icon($notification->getIcon())
            ->timeToLeave($notification->getTimeToLeave())
            ->clickAction($notification->getClickAction());
    }

    /**
     * @inheritDoc
     * @throws GuzzleException
     */
    public function send(): stdClass
    {
        try {
            $client = new Client();
            $contents = $client->request($this->method, $this->url, [
                'headers' => $this->headers,
                'body' => $this->createBody(),
            ])->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw $e;
        }
        return json_decode($contents);
    }

    /**
     * @inheritDoc
     */
    public function key(string $key): self
    {
        return $this->header([
            'Authorization' => 'key='.$key,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function notification(): self
    {
        $this->mode = self::MODE_NOTIFICATION;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(): self
    {
        $this->mode = self::MODE_DATA;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function to(FcmToken $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function title(FcmTitle $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function body(FcmBody $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function icon(FcmIcon $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function timeToLeave(FcmTimeToLeave $timeToLeave): self
    {
        $this->timeToLeave = $timeToLeave;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clickAction(FcmClickAction $clickAction): self
    {
        $this->clickAction = $clickAction;
        return $this;
    }

    /**
     * @return string
     */
    protected function createBody(): string
    {
        return Json::encode([
            'to' => (string) $this->token,
            'time_to_live' => $this->timeToLeave->toInt(),
            $this->mode => [
                'title' => (string) $this->title,
                'body' => (string) $this->body,
                'icon' => (string) $this->icon,
                'click_action' => (string) $this->clickAction,
            ],
        ]);
    }
}
