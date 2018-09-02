<?php

namespace discounts\DiscountConditions;

use discounts\DiscountConditions\ConditionInterface;
use discounts\Items\ItemList;
use discounts\Items\Switches as SwitchItem;

/**
 * Implements the switches condition (items exists and are of category Switches).
 *
 * @package DiscountConditions
 */
class Switches extends Condition implements ConditionInterface
{
    /**
     * Switches constructor.
     *
     * @param ItemList $itemList
     */
    public function __construct(ItemList $itemList)
    {
        $items = $itemList->getItems();

        $this->applyCondition($items);
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

    /**
     * Apply condition.
     *
     * @param array $items
     */
    private function applyCondition(array $items)
    {
        foreach ($items as $item) {
            if ($item instanceof SwitchItem) {
                $this->items[] = $item;
            }
        }
    }
}