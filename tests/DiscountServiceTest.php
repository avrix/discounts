<?php

namespace discounts\tests;

use discounts\Order;
use discounts\DiscountConditions\Switches;
use discounts\DiscountConditions\Tools;
use PHPUnit\Framework\TestCase;
use discounts\DiscountService;
/**
 * Class DiscountServiceTest.
 */
class DiscountServiceTest extends TestCase
{
    protected $orders;

    protected function setUp()
    {
        // 10 switches
        $this->orders[1] = json_decode(file_get_contents(__DIR__ .'/../data/order1.json'), true);
        // 5 switches, customer revenue > 1000
        $this->orders[2] = json_decode(file_get_contents(__DIR__ .'/../data/order2.json'), true);
        // tools mix
        $this->orders[3] = json_decode(file_get_contents(__DIR__ .'/../data/order3.json'), true);
    }

    #region --- Discount Conditions ---

    public function testSwitchesCondition()
    {
        $orderItems = (new Order($this->orders[1]))->getItems();
        $switches = new Switches($orderItems);
        $this->assertEquals(true, $switches->isFulfilled());

        $orderItems = (new Order($this->orders[3]))->getItems();
        $switches = new Switches($orderItems);
        $this->assertEquals(false, $switches->isFulfilled());
    }

    public function testToolsCondition()
    {
        $orderItems = (new Order($this->orders[1]))->getItems();
        $tools = new Tools($orderItems);
        $this->assertEquals(false, $tools->isFulfilled());

        $orderItems = (new Order($this->orders[3]))->getItems();
        $tools = new Tools($orderItems);
        $this->assertEquals(true, $tools->isFulfilled());
    }

    #endregion

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