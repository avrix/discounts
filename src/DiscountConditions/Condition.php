<?php

namespace discounts\DiscountConditions;

abstract class Condition
{
    /**
     * List of items for current condition.
     *
     * @var array
     */
    protected $items = [];

    /**
     * @return bool
     */
    abstract public function isFulfilled(): bool;
}