<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;


final class FcmNotification
{
    /** @var FcmToken */
    private $token;
    /** @var FcmTitle */
    private $title;
    /** @var FcmBody */
    private $body;
    /** @var FcmIcon */
    private $icon;
    /** @var FcmTimeToLeave */
    private $timeToLeave;
    /** @var FcmClickAction */
    private $clickAction;

    public function __construct(
        FcmToken $token,
        FcmTitle $title,
        FcmBody $body,
        FcmIcon $icon,
        FcmTimeToLeave $timeToLeave,
        FcmClickAction $clickAction
    ) {
        $this->token = $token;
        $this->title = $title;
        $this->body = $body;
        $this->icon = $icon;
        $this->timeToLeave = $timeToLeave;
        $this->clickAction = $clickAction;
    }

    /**
     * @return FcmToken
     */
    public function getToken(): FcmToken
    {
        return $this->token;
    }

    /**
     * @return FcmTitle
     */
    public function getTitle(): FcmTitle
    {
        return $this->title;
    }

    /**
     * @return FcmBody
     */
    public function getBody(): FcmBody
    {
        return $this->body;
    }

    /**
     * @return FcmIcon
     */
    public function getIcon(): FcmIcon
    {
        return $this->icon;
    }

    /**
     * @return FcmTimeToLeave
     */
    public function getTimeToLeave(): FcmTimeToLeave
    {
        return $this->timeToLeave;
    }

    /**
     * @return FcmClickAction
     */
    public function getClickAction(): FcmClickAction
    {
        return $this->clickAction;
    }
}
