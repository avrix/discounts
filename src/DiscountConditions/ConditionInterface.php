<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 02.09.2018
 * Time: 21:05
 */

namespace discounts\DiscountConditions;


interface ConditionInterface
{

    public function isFulfilled(): bool;

    public function getItems(): array;
}