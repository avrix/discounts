<?php

namespace discounts\Discounts;

use discounts\Order;

/**
 * Compute discount data.
 */
class Discount
{

    /**
     * @var Order
     */
    private $order;

    /**
     * Discount constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @param int $percent
     * @return array
     */
    public function percentOffOrder(int $percent)
    {
        $discountValue = ($percent / 100) * $this->order->getTotal();

        $discountData = [
            'discount_type' => 'percent_off_order',
            'discount_value' => [
                'quantity' => 1,
                'value'    => $discountValue]
        ];

        return $discountData;
    }

    /**
     * @param string $itemId
     * @param int $quantity
     * @return array
     */
    public function itemsForFree(string $itemId, int $quantity)
    {
        $discountData = [
            'discount_type' => 'items_for_free',
            'discount_value' => [
                'quantity' => $quantity,
                'value' => $itemId
            ]
        ];

        return $discountData;
    }

}