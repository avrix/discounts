<?php

namespace discounts\Discounts;

use discounts\Customer;
use discounts\DiscountConditions\CustomerRevenue as CustomerRevenueCondition;

class CustomerRevenue extends Discount
{
    const PERCENT_DISCOUNT_VALUE = 10;

    /**
     * @var CustomerRevenueCondition
     */
    private $customerRevenueCondition;

    /**
     * CustomerRevenue constructor.
     *
     * @param Customer $customer
     * @param int $minimumCount
     */
    public function __construct(Customer $customer, int $minimumCount)
    {
        $this->customerRevenueCondition = new CustomerRevenueCondition($customer, $minimumCount);
    }

    /**
     * Get discounts data.
     *
     * @return array
     */
    public function getDiscounts(): array
    {
        $discounts = [];

        if ($this->customerRevenueCondition->isFulfilled()) {
            return $this->getPercentOffOrder(self::PERCENT_DISCOUNT_VALUE);
        }
    }
}