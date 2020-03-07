<?php
declare(strict_types=1);

namespace App\Services\Send\Fcm;


use App\Domain\ValueObject\FcmBody;
use App\Domain\ValueObject\FcmClickAction;
use App\Domain\ValueObject\FcmIcon;
use App\Domain\ValueObject\FcmTimeToLeave;
use App\Domain\ValueObject\FcmTitle;
use App\Domain\ValueObject\FcmToken;

interface FcmSendInterface
{
    /**
     * @param string $key
     * @return FcmSendService
     */
    public function key(string $key): FcmSendService;

    /**
     * @return FcmSendService
     */
    public function notification(): FcmSendService;

    /**
     * @return FcmSendService
     */
    public function data(): FcmSendService;

    /**
     * @param FcmToken $token
     * @return FcmSendService
     */
    public function to(FcmToken $token): FcmSendService;

    /**
     * @param FcmTitle $title
     * @return FcmSendService
     */
    public function title(FcmTitle $title): FcmSendService;

    /**
     * @param FcmBody $body
     * @return FcmSendService
     */
    public function body(FcmBody $body): FcmSendService;

    /**
     * @param FcmIcon $icon
     * @return FcmSendService
     */
    public function icon(FcmIcon $icon): FcmSendService;

    /**
     * @param FcmTimeToLeave $timeToLeave
     * @return FcmSendService
     */
    public function timeToLeave(FcmTimeToLeave $timeToLeave): FcmSendService;

    /**
     * @param FcmClickAction $clickAction
     * @return FcmSendService
     */
    public function clickAction(FcmClickAction $clickAction): FcmSendService;
}
