<?php
declare(strict_types=1);

namespace App\Domain\Collection;


use App\Domain\ValueObject\FcmToken;
use Illuminate\Support\Collection;

final class FcmTokenCollection extends Collection
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
        /** @var FcmToken $item */
        foreach ($this->items as $item) {
            $values[] = (string) $item;
        }
        return $values;
    }

    /**
     * @param FcmToken $fcmToken
     * @return bool
     */
    public function existItem(FcmToken $fcmToken): bool
    {
        /** @var FcmToken $item */
        foreach ($this->items as $item) {
            if ($item->equal($fcmToken)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $items
     * @throws \Exception
     */
    private function validate(array $items)
    {
        foreach ($items as $item) {
            if (($item instanceof FcmToken) === false) {
                throw new \Exception('FcmTokenCollection\'s item must be FcmToken');
            }
        }
    }
}
