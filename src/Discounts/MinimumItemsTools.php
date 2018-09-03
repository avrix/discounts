<?php

namespace discounts\Discounts;


use discounts\DiscountConditions\Decorators\MinimumItems;
use discounts\DiscountConditions\Tools;
use discounts\Items\ItemList;

class MinimumItemsTools extends Discount
{

    /**
     * @var MinimumItems
     */
    private $discountCondition;

    /**
     * MinimumItemsTools constructor.
     *
     * @param ItemList $items
     * @param $minimumCount
     */
    public function __construct(ItemList $items, $minimumCount)
    {
        $toolsCondition = new Tools($items);

        $this->discountCondition = new MinimumItems($toolsCondition, $minimumCount);
    }

    /**
     * @return array
     */
    public function getDiscounts(): array
    {
        $discounts = [];

        if ($this->discountCondition->isFulfilled()) {
            $matchedItems = $this->discountCondition->getItemsHavingConditionFulfilled();

            $discounts[] = $this->getItemsForFree($this->getCheapestItem($matchedItems), 1);
        }

        return $discounts;
    }

    /**
     * Get id of item with the lowest price.
     *
     * @param $items
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