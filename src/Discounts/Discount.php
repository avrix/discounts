<?php

namespace discounts\Discounts;

use discounts\DiscountConditions\ConditionInterface;

/**
 * Abstract discount class.
 */
abstract class Discount
{
    /**
     * @var ConditionInterface
     */
    protected $discountCondition;

    /**
     * Compute discounts for order.
     *
     * @return array
     */
    public abstract function getDiscounts(): array;

    /**
     * Return discount offering a percent off order value.
     *
     * @param int   $percent
     * @param float $totalValue
     *
     * @return array
     */
    public function getPercentOffOrder(int $percent, $totalValue): array
    {
        $discountValue = ($percent / 100) * $totalValue;

        $discountData = [
            'discount_type'  => 'percent_off_order',
            'discount_value' => [
                'quantity' => 1,
                'value'    => $discountValue,
            ],
        ];

        return $discountData;
    }

    /**
     * Return discount offering a number of free items.
     *
     * @param string $itemId
     * @param int    $quantity
     *
     * @return array
     */
    public function getItemsForFree(string $itemId, int $quantity): array
    {
        $discountData = [
            'discount_type'  => 'items_for_free',
            'discount_value' => [
                'quantity' => $quantity,
                'value'    => $itemId,
            ],
        ];

        return $discountData;
    }
}