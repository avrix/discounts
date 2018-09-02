<?php

namespace discounts\DiscountConditions\Decorators;


use discounts\DiscountConditions\Condition;

abstract class ConditionDecorator extends Condition
{

    public $item;
}