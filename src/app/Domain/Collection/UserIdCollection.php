<?php
declare(strict_types=1);

namespace App\Domain\Collection;


use App\Domain\ValueObject\UserId;
use Illuminate\Support\Collection;

final class UserIdCollection extends Collection
{
    /**
     * TaskStatusCollection constructor.
     *
     * @param array $items
     * @throws \Exception
     */
    public function __construct(array $items = [])
    {
        self::validate($items);
        parent::__construct($items);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $values = [];
        /** @var UserId $item */
        foreach ($this->items as $item) {
            $values[] = $item->toInt();
        }
        return $values;
    }

    /**
     * @param array $items
     * @throws \Exception
     */
    private function validate(array $items)
    {
        foreach ($items as $item) {
            if (($item instanceof UserId) === false) {
                throw new \Exception('UserIdCollection\'s item must be UserId');
            }
        }
    }
}
