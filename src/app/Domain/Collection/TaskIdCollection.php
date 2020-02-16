<?php


namespace App\Domain\Collection;


use App\Domain\ValueObject\TaskId;
use Illuminate\Support\Collection;

final class TaskIdCollection extends Collection
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
        /** @var TaskId $item */
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
            if (($item instanceof TaskId) === false) {
                throw new \Exception('TaskIdCollection\'s item must be TaskId');
            }
        }
    }
}
