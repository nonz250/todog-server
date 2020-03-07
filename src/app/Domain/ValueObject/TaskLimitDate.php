<?php
declare(strict_types=1);

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
    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $format
     * @return string
     */
    public function format(string $format): ?string
    {
        if (is_null($this->value)) {
            return null;
        }
        return Carbon::parse($this->value)->format($format);
    }

    /**
     * @return Carbon
     */
    public function toCarbon(): ?Carbon
    {
        if (is_null($this->value)) {
            return null;
        }
        return Carbon::parse($this->value);
    }
}
