<?php

namespace discounts\DiscountConditions;

use Condition;
use discounts\Customer;
use discounts\DiscountConditions\Condition as AbstractCondition;

class CustomerRevenue extends AbstractCondition
{
    /**
     * The customer for which the condition must be verified.
     *
     * @var Customer
     */
    private $customer;

    /**
     * Revenue limit.
     *
     * @var int
     */
    private $revenueLimit;

    /**
     * Class constructor.
     *
     * @param Customer $customer
     * @param int $revenueLimit
     */
    public function __construct(Customer $customer, int $revenueLimit)
    {
        $this->customer = $customer;
        $this->revenueLimit = $revenueLimit;
    }

    /**
     * Check customer revenue condition.
     *
     * @return bool
     */
    public function isFulfilled(): bool
    {
        return $this->customer->getRevenue() >= $this->revenueLimit;
    }
}