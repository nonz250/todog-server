<?php


namespace App\Domain\Collection;


use App\Domain\ValueObject\TaskStatus;
use Illuminate\Support\Collection;

final class TaskStatusCollection extends Collection
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
        /** @var TaskStatus $item */
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
            if (($item instanceof TaskStatus) === false) {
                throw new \Exception('TaskStatusCollection\'s item must be TaskStatus');
            }
        }
    }
}
