<?php


namespace App\Domain\ValueObject;


final class TaskId
{
    /**
     * @var int
     */
    private $value;

    /**
     * TaskStatus constructor.
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
        return (int) $this->value;
    }
}
