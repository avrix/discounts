<?php
/**
 * This file is part of StaffCloud.
 *
 * @copyright  Copyright (c) 2015 and Onwards, Smartbridge AG <info@smartbridge.ch>. All rights reserved.
 * @license    Proprietary/Closed Source
 * @see        http://www.staff.cloud
 */

namespace discounts\tests;

use PHPUnit\Framework\TestCase;
use discounts\DiscountService;
/**
 * Class DiscountServiceTest.
 */
class DiscountServiceTest extends TestCase
{
    public function testCustomerRevenueOrderHasPercentOfOrderDiscount()
    {
        $order = file_get_contents(__DIR__ .'/../data/order2.json');
        $discounts = [
            'order_id' => '2',
            'customer_id' => '2',
            'discounts' => [
                [
                    'discount_type' => 'percent_off_order',
                    'discount_value' => ['quantity' => 1, 'value' => 2.495],
                ],
                [
                    'discount_type' => 'items_for_free',
                    'discount_value' => ['quantity' => 1, 'value' => 'B102'],
                ],
            ]
        ];

        $discountService = new DiscountService();
        $result = $discountService->processOrder($order);

        $this->assertEquals($result, $discounts);
    }

    public function testFiveIdenticalSwitchesOrderHasItemsForFreeDiscount()
    {
        $order = file_get_contents(__DIR__ .'/../data/order1.json');
        $discounts = [
            [
                'discount_type' => 'items_for_free',
                'discount_value' => ['quantity' => 1, 'value' => 'B102'],
            ],
        ];

        $discountService = new DiscountService();
        $result = $discountService->processOrder($order);

        $this->assertEquals($result['discounts'], $discounts);
    }


    public function testTwoOrMoreToolsOrderHasItemsForFreeDiscount()
    {
        $order = file_get_contents(__DIR__ .'/../data/order3.json');
        $discounts = [
            [
                'discount_type' => 'items_for_free',
                'discount_value' => ['quantity' => 1, 'value' => 'A101'],
            ],
        ];

        $discountService = new DiscountService();
        $result = $discountService->processOrder($order);

        $this->assertEquals($result['discounts'], $discounts);
    }
}