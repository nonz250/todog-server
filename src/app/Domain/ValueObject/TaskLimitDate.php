<?php


namespace App\Domain\ValueObject;


use Carbon\Carbon;

final class TaskLimitDate
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
     * @param string $format
     * @return string
     */
    public function format(string $format): string
    {
        return Carbon::parse($this->value)->format($format);
    }

    /**
     * @return Carbon
     */
    public function toCarbon(): Carbon
    {
        return Carbon::parse($this->value);
    }
}
