<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;


final class FcmTimeToLeave
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function toInt(): int
    {
        return (int) $this->value;
    }
}
