<?php

namespace discounts\tests;

use discounts\Customer;
use discounts\Items\ItemList;
use discounts\Items\Switches as SwitchesItems;
use discounts\Items\Tools as ToolsItems;
use discounts\Order;
use discounts\DiscountConditions\Switches;
use discounts\DiscountConditions\Tools;
use PHPUnit\Framework\TestCase;
use discounts\DiscountService;
use Exception;
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
        // 2 switches, 10 tools
        $this->orders[4] = json_decode(file_get_contents(__DIR__ .'/../data/order4.json'), true);
    }

    #region --- Test Customer component ---
    public function testCustomerDoesNotExist()
    {
        $this->expectException(Exception::class);

        $customer = new Customer(999);
    }

    public function testCustomerIsCorrect()
    {
        $customerId = 1;
        $customer = new Customer(1);

        $this->assertEquals($customerId, $customer->getId());
    }
    #endregion

    #region --- Test ItemList component ---
    public function testItemListHasCorrectItems()
    {
        $itemList = new ItemList($this->orders[4]['items']);
        $items = $itemList->getItems();

        $this->assertEquals(count($this->orders[4]['items']), count($items));
        $this->assertInstanceOf(ToolsItems::class, $items[0]);
        $this->assertInstanceOf(SwitchesItems::class, $items[1]);
    }

    #endregion

    #region --- Test Order component ---
    public function testOrderHasCorrectCustomer()
    {
        $order = new Order($this->orders[3]);

        $this->assertInstanceOf(Customer::class, $order->getCustomer());
        $this->assertEquals($this->orders[3]['customer-id'], $order->getCustomer()->getId());
    }

    public function testGetItemForCategoryHasCorrectCategory()
    {
        $order = new Order($this->orders[4]);

        $item = $order->getItemForCategory(SwitchesItems::ITEM_CATEGORY);

        $this->assertInstanceOf(SwitchesItems::class, $item);
    }
    #endregion
    #region --- Discount Conditions ---

    public function testOrderHasSwitches()
    {
        $orderItems = (new Order($this->orders[4]))->getItems();
        $switches = new Switches($orderItems);

        $this->assertTrue($switches->isFulfilled());
    }

    public function testOrderDoesntHaveSwitches()
    {
        $orderItems = (new Order($this->orders[3]))->getItems();
        $switches = new Switches($orderItems);

        $this->assertFalse($switches->isFulfilled());
    }

    public function testOrderHasTools()
    {
        $orderItems = (new Order($this->orders[4]))->getItems();
        $tools = new Tools($orderItems);

        $this->assertTrue($tools->isFulfilled());
    }

    public function testOrderDoesntHaveTools()
    {
        $orderItems = (new Order($this->orders[1]))->getItems();
        $tools = new Tools($orderItems);

        $this->assertFalse($tools->isFulfilled());
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