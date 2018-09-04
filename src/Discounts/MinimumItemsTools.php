<?php

namespace discounts\Discounts;

use discounts\DiscountConditions\Decorators\MinimumItems;
use discounts\DiscountConditions\Tools;
use discounts\Items\ItemList;
use discounts\Order;

/**
 * Discount rule implementation.
 * Every order having a minimum number of items
 * from category Tools gets the cheapest for free.
 */
class MinimumItemsTools extends Discount
{
    /**
     * Minimum items of category tools.
     */
    const MINIMUM_NUMBER = 2;

    /**
     * Free items offered at discount.
     */
    const FREE_ITEMS = 1;

    /**
     * @var MinimumItems
     */
    protected $discountCondition;

    /**
     * MinimumItemsTools constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $toolsCondition = new Tools($order->getItems());

        $this->discountCondition = new MinimumItems($toolsCondition, self::MINIMUM_NUMBER);
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
            $matchedItems = $this->discountCondition->getItemsHavingConditionFulfilled();

            $discounts[] = $this->getItemsForFree($this->getCheapestItem($matchedItems), self::FREE_ITEMS);
        }

        return $discounts;
    }

    /**
     * Get id of item with the lowest price.
     *
     * @param $items
     *
     * @return int
     */
    private function getCheapestItem($items)
    {
        $list = [];
        foreach ($items as $item) {
            $list[$item->id] = $item->price;
        }

        asort($list);
        reset($list);

        return key($list);
    }
}