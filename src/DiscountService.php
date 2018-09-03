<?php

namespace discounts;

use discounts\Discounts\CustomerRevenue;
use discounts\Discounts\MinimumItemsTools;
use discounts\Discounts\MinimumIdenticalSwitches;

/**
 * Process customer order and determine relevant discounts.
 *
 * @package discounts
 */
class DiscountService
{
    /**
     * Process order and get discounts.
     *
     * @param string $orderData
     * @return array|void
     */
    public function processOrder(string $orderData): array
    {
        $order = new Order(json_decode($orderData, true));
        $discounts = $this->getDiscounts($order);

        return $discounts;
    }

    /**
     * Get discounts for order;
     *
     * @param Order $order
     * @return array
     */
    private function getDiscounts(Order $order): array
    {
        $discounts = [];
        $discounts[] = (new CustomerRevenue($order->getCustomer(), 1000))->getDiscounts();
        $discounts[] = (new MinimumIdenticalSwitches($order->getItems(), 5))->getDiscounts();
        $discounts[] = (new MinimumItemsTools($order->getItems(), 2))->getDiscounts();

        return $this->getDiscountResponse($discounts, $order);
    }


    /**
     * Discount response wrapper.
     *
     * @param array $discounts
     * @param $order
     * @return array
     */
    private function getDiscountResponse(array $discounts, $order)
    {
        return [
            'order_id' => $order->getId(),
            'customer_id' => $order->getCustomer()->getId(),
            'discounts' => array_filter($discounts)
        ];
    }
}