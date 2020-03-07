<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;


final class FcmToken
{
    /**
     * @var string
     */
    private $value;

    /**
     * TaskName constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param FcmToken $fcmToken
     * @return bool
     */
    public function equal(FcmToken $fcmToken): bool
    {
        return (string) $this->value === (string) $fcmToken->value;
    }
}
