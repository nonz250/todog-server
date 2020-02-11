<?php


namespace App\Domain\ValueObject;


use http\Exception\InvalidArgumentException;

final class QueryParamTaskStatus
{
    public const RULE_ALL = 'all';
    public const RULE_ENABLED = 'enabled';
    public const RULE_DISABLED = 'disabled';
    public const RULES = [self::RULE_ALL, self::RULE_ENABLED, self::RULE_DISABLED];

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
        self::validate($value);
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
     * @return bool
     */
    public function isAll(): bool
    {
        return $this->value === self::RULE_ALL;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->value === self::RULE_ENABLED;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->value === self::RULE_DISABLED;
    }

    /**
     * @param string $value
     */
    private static function validate(string $value): void
    {
        if (!in_array($value, self::RULES)) {
            throw new InvalidArgumentException(
                sprintf('状態は%sのいずれかを入力してください。', implode(', ', self::RULES))
            );
        }
    }
}
