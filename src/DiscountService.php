<?php

namespace discounts;

use discounts\DiscountConditions\Decorators\MinimumIdenticalItems;
use discounts\DiscountConditions\Decorators\MinimumItems;
use discounts\DiscountConditions\Switches;
use discounts\DiscountConditions\Tools;
use discounts\DiscountConditions\CustomerRevenue;
use discounts\Discounts\CustomerDiscount;

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

        $customerRevenueCondition = new CustomerRevenue($order->getCustomer(), 1000);

        if ($customerRevenueCondition->isFulfilled()) {
            $discounts[] = (new Discount($order))->percentOffOrder(10);
        }

        $switchesCondition = new MinimumIdenticalItems(new Switches($order->getItems()), 5);
        if ($switchesCondition->isFulfilled()) {
            foreach ($switchesCondition->getItemsHavingConditionFulfilled() as $item)
                $discounts[] = (new Discount($order))->itemsForFree($item->id, 1);
        }

        $toolsCondition = new MinimumItems(new Tools($order->getItems()), 2);
        if ($toolsCondition->isFulfilled()) {
            $matchedItems = $toolsCondition->getItemsHavingConditionFulfilled();

            $discounts[] = (new Discount($order))->itemsForFree($this->getCheapestItem($matchedItems), 1);
        }

        return $this->getDiscountResponse($discounts, $order);
    }

    /**
     * Get id of item with the lowest price.
     *
     * @param $items
     * @return int
     */
    private function getCheapestItem($items)
    {
        $list = [];
        foreach ($items as $item) {
            $list[$item->id] = $item->price;
        }

        asort($list);
        reset($list);

        return key($list);
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
            'discounts' => $discounts
        ];
    }
}