<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 02.09.2018
 * Time: 14:27
 */

namespace discounts\DiscountConditions\Decorators;

use discounts\DiscountConditions\Condition;
use discounts\DiscountConditions\ConditionInterface;

class MinimumIdenticalItems extends ConditionDecorator
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
     * Keep list of items that fulfills the condition.
     *
     * @var array
     */
    private $matchingItems = [];

    /**
     * MinimumIdenticalItems constructor.
     *
     * @param ConditionInterface $condition
     * @param int                $minimumCount
     */
    public function __construct(ConditionInterface $condition, int $minimumCount)
    {
        $this->itemTypeCondition = $condition;
        $this->minimumCount = $minimumCount;

        $this->applyCondition();
    }

    /**
     * @return bool
     */
    public function isFulfilled(): bool
    {
        if ($this->itemTypeCondition->isFulfilled()) {
            return !empty($this->matchingItems);
        }

        return false;
    }

    /**
     * Return matching items.
     *
     * @return array
     */
    public function getItemsHavingConditionFulfilled(): array
    {
        return $this->matchingItems;
    }

    /**
     * Apply condition.
     */
    private function applyCondition()
    {
        $items = $this->itemTypeCondition->getItems();

        foreach ($items as $item) {
            if ($item->quantity >= $this->minimumCount) {
                $this->matchingItems[] = $item;
            }
        }
    }
}