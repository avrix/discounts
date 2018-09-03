<?php

namespace discounts\Discounts;

use discounts\DiscountConditions\Decorators\MinimumIdenticalItems;
use discounts\DiscountConditions\Switches;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MinimumIdenticalSwitches extends Discount
{
    /**
     * @var MinimumIdenticalItems
     */
    private $discountCondition;

    /**
     * MinimumIdenticalSwitches constructor.
     *
     * @param $items
     * @param $minimumCount
     */
    public function __construct($items, $minimumCount)
    {
        $switchesCondition = new Switches($items);
        $this->discountCondition = new MinimumIdenticalItems($this->switchesCondition);

        return $this;
    }

    /**
     * @return array
     */
    public function getDiscounts(): array
    {
        $discounts = [];

        if ($this->discountCondition->isFulfilled()) {
            foreach ($this->discountCondition->getItemsHavingConditionFulfilled() as $item)
                $discounts[] = $this->getItemsForFree($item->id, 1);
        }

        return $discounts;
    }
}