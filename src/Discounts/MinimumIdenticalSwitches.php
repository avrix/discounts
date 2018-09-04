<?php

namespace discounts\Discounts;

use discounts\DiscountConditions\Decorators\MinimumIdenticalItems;
use discounts\DiscountConditions\Switches;
use discounts\Order;

/**
 * Discount rule implementation.
 * Every order with minimum number of identical switches gets an item for free
 */
class MinimumIdenticalSwitches extends Discount
{
    /**
     * Minimum identical switches.
     */
    const MINIMUM_NUMBER = 5;

    /**
     * Free items offered at discount.
     */
    const FREE_ITEMS = 1;

    /**
     * @var MinimumIdenticalItems
     */
    protected $discountCondition;

    /**
     * MinimumIdenticalSwitches constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $switchesCondition = new Switches($order->getItems());
        $this->discountCondition = new MinimumIdenticalItems($switchesCondition, self::MINIMUM_NUMBER);

        return $this;
    }

    /**
     * Compute discounts for order.
     *
     * @return array
     */
    public function getDiscounts(): array
    {
        $discounts = [];

        if ($this->discountCondition->isFulfilled()) {
            foreach ($this->discountCondition->getItemsHavingConditionFulfilled() as $item)
                $discounts[] = $this->getItemsForFree($item->id, self::FREE_ITEMS);
        }

        return $discounts;
    }
}