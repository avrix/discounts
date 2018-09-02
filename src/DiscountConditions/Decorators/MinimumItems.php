<?php

namespace discounts\DiscountConditions\Decorators;


use discounts\DiscountConditions\Condition;
use discounts\DiscountConditions\ConditionInterface;

/**
 * Class implementation for minimum quantity of given item.
 *
 * @package DiscountConditions
 */
class MinimumItems extends ConditionDecorator
{
    /**
     * @var Condition
     */
    private $itemTypeCondition;

    /**
     * @var int
     */
    private $minimumCount;

    /**
     * Items count after applying condition.
     *
     * @var int
     */
    private $count = 0;

    /**
     * MinimumItems constructor.
     *
     * @param ConditionInterface $condition
     * @param int $minimumCount
     */
    public function __construct(ConditionInterface $condition, int $minimumCount)
    {
        $this->itemTypeCondition = $condition;
        $this->minimumCount = $minimumCount;

        $this->applyCondition();
    }


    /**
     * Check condition.
     *
     * @return bool
     */
    public function isFulfilled(): bool
    {
        if ($this->itemTypeCondition->isFulfilled()) {

            return $this->count >= $this->minimumCount;
        }

        return false;
    }

    /**
     * Return matching items.
     *
     * @return array
     */
    public function getItemsHavingConditionFulfilled()
    {
        return $this->itemTypeCondition->getItems();
    }

    /**
     * Apply condition.
     */
    private function applyCondition()
    {
        foreach ($this->itemTypeCondition->getItems() as $item) {
            $this->count += $item->quantity;
        }
    }
}