<?php


namespace App\Domain\ValueObject;


final class UserId
{
    /**
     * @var int
     */
    private $value;

    /**
     * UserId constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return (int)$this->value;
    }
}
