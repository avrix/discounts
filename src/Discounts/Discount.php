<?php

namespace discounts\Discounts;

use discounts\DiscountConditions\ConditionInterface;
use discounts\Order;

/**
 * Compute discount data.
 */
abstract class Discount
{

    /**
     * @var ConditionInterface
     */
    protected $discountCondition;

    /**
     * @return array
     */
    public abstract function getDiscounts(): array;

    /**
     * @param int $percent
     * @return array
     */
    public function getPercentOffOrder(int $percent): array
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
     *
     * @return array
     */
    public function getItemsForFree(string $itemId, int $quantity): array
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