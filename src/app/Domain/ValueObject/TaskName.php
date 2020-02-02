<?php


namespace App\Domain\ValueObject;


final class TaskName
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
}
