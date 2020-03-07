<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;


final class FcmIcon
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
