<?php

namespace discounts\Discounts;

use discounts\DiscountConditions\CustomerRevenue as CustomerRevenueCondition;
use discounts\Order;

/**
 * Discount rule implementation.
 * Every customer with a revenue > 1000 gets a percent off his order value.
 */
class CustomerRevenue extends Discount
{
    /**
     * Minimum revenue value to get the discount.
     */
    const CUSTOMER_REVENUE_LIMIT = 1000;

    /**
     * Percent value offered at discount.
     */
    const PERCENT_DISCOUNT_VALUE = 10;

    /**
     * @var CustomerRevenueCondition
     */
    protected $discountCondition;

    /**
     * Holder for order total value.
     *
     * @var float
     */
    protected $orderTotal;

    /**
     * CustomerRevenue constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $customer = $order->getCustomer();
        $this->orderTotal = $order->getTotal();
        $this->discountCondition = new CustomerRevenueCondition($customer, self::CUSTOMER_REVENUE_LIMIT);
    }

    /**
     * Compute discounts for order.
     *
     * @return array
     */
    public function getDiscounts(): array
    {
        $discounts = [];

        if ($this->discountCondition->isFulfilled()) {
            $discounts[] = $this->getPercentOffOrder(self::PERCENT_DISCOUNT_VALUE, $this->orderTotal);
        }

        return $discounts;
    }
}