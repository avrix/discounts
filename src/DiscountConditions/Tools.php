<?php

namespace discounts\DiscountConditions;


use discounts\DiscountConditions\Condition;
use discounts\DiscountConditions\ConditionInterface;
use discounts\Items\ItemList;
use discounts\Items\Tools as ToolsItem;

/**
 * Class Tools
 * @package DiscountConditions
 */
class Tools extends Condition implements ConditionInterface
{
    /**
     * Tools constructor.
     *
     * @param ItemList $itemList
     */
    public function __construct(ItemList $itemList)
    {
        $items = $itemList->getItems();

        // filter only Tools items to apply condition.
        foreach ($items as $item) {
            if ($item instanceof ToolsItem) {
                $this->items[] = $item;
            }
        }
    }

    /**
     *
     * @return bool
     */
    public function isFulfilled(): bool
    {
        return count($this->items) ? true : false;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}